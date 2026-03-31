<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class AwardController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view-any', Award::class);
        $event = Event::query()->where(["campus" => Auth::user()->campus->value, "status" => "OPENED"])->first();
        $pastEvent = Event::query()->where(["campus" => Auth::user()->campus->value])->get();

        $selectedEventId = $request->event_id;

        if (!$event && !$selectedEventId) {
            return view('awards.index', ['awards' => [], 'event' => null]);
        }
        $query = Award::query();
        if (!$selectedEventId) {
            $query = $query
                ->with('events')
                ->where('awards.campus', Auth::user()->campus)
                ->whereHas('events', function ($q) {
                    $q->where('status', 'OPENED')
                        ->where('campus', Auth::user()->campus);
                });
        } else {
            $event = Event::query()->where("events.id", $selectedEventId)->first();
            $query = $query
                ->with('events')
                ->where('awards.campus', Auth::user()->campus)
                ->whereHas('events', function ($q) use ($event) {
                    $q->where('events.id', $event->id);
                });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $awards = $query->paginate(10)->withQueryString();
        return view('awards.index', ['awards' => $awards, 'event' => $event, 'pastEvent' => $pastEvent]);
    }

    public function create()
    {
        Gate::authorize('create', Award::class);
        return view('awards.create', [
            'award' => new Award(),
        ]);
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Award::class);

        $campus = Auth::user()->campus;
        Cache::forget("all_award_names_{$campus->value}");

        $activeEvent = Event::where("campus", $campus->value)
            ->where("status", "OPENED")
            ->first();

        if (!$activeEvent) {
            return redirect()->back()->withErrors(['error' => 'ไม่พบช่วงเวลาการสมัครที่เปิดอยู่']);
        }

        if ($request->has('name')) {
            $request->merge([
                'name' => trim($request->input('name'))
            ]);
        }

        $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'min:3',
                    function ($attribute, $value, $fail) use ($campus, $activeEvent) {
                        $exists = Award::where('name', $value)
                            ->where('campus', $campus->value)
                            ->whereHas('events', function ($q) use ($activeEvent) {
                                $q->where('events.id', $activeEvent->id);
                            })
                            ->exists();

                        if ($exists) {
                            $fail('ชื่อหมวดรางวัลซ้ำ! มีชื่อนี้อยู่แล้วในรอบการสมัครปัจจุบัน');
                        }
                    }
                ],
                'application_document' => ['required', 'mimes:pdf', 'max:10240', 'file'],
                'requirements' => ['nullable', 'array'],

                'requirements.*.id' => ['required_with:requirements', 'string', 'max:50', 'distinct'],
                'requirements.*.name' => ['required_with:requirements', 'string', 'max:255'],
                'requirements.*.required' => ['required_with:requirements', 'boolean'],
            ],
            [
                'name.required' => "โปรดกรอกชื่อหมวดรางวัล",
                'name.min' => "โปรดใส่ชื่อหมวดรางวัลอย่างน้อย 3 ตัวอักษร",
                'application_document.required' => "โปรดอัปโหลดเอกสารใบสมัคร",
                'application_document' => "โปรดอัปโหลดเอกสารที่ถูกต้องตามข้อกำหนด",
                'requirements.*.required' => "โปรดกรอกเอกสารเพิ่มเติมให้ถูกต้อง"
            ]
        );

        $file = $request->file('application_document');
        $uploadRequest = new Request();
        $uploadRequest->merge([
            'folder' => $campus->value,
        ]);

        $uploadRequest->files->set('file', $file);
        $path = MinioController::uploadFile($uploadRequest);

        $award = new Award();
        $award->name = $request->input('name');
        $award->form_path = $path;
        $award->campus = $campus->value;

        $requirements = collect($request->input('requirements', []))
            ->filter(fn($field) => !empty($field['name']))
            ->map(fn($field) => [
                'id' => $field['id'],
                'name' => $field['name'],
                'required' => (bool)$field['required'],
            ])
            ->values()
            ->toArray();

        $award->requirements = $requirements;
        $award->save();

        $award->events()->attach($activeEvent->id);

        return redirect()->route('awards.index');
    }
    public function copy(Award $award)
    {
        Gate::authorize('create', Award::class);

        $campus = Auth::user()->campus;
        Cache::forget("all_award_names_{$campus->value}");


        $newAward = new Award();
        $newAward->name = $award->name;

        $newAward->form_path = $award->form_path;
        $newAward->campus = Auth::getUser()->campus->value;
        $newAward->requirements = $award->requirements;
        $newAward->save();

        $activeEvent = Event::where("campus", Auth::user()->campus->value)
            ->where("status", "OPENED")
            ->first();

        if (!$activeEvent) {
            return redirect()->back()->withErrors(['error' => 'ไม่พบช่วงเวลาการสมัครที่เปิดอยู่']);
        }
        $newAward->events()->attach($activeEvent->id);

        return redirect()->route('awards.index');
    }

    public function edit(Award $award)
    {
        Gate::authorize('update', $award);
        return view('awards.edit', ['award' => $award]);
    }
    public function show(Award $award)
    {
        return view('awards.show', ['award' => $award]);
    }

    public function update(Request $request, Award $award)
    {
        Gate::authorize('update', $award);

        $campus = Auth::user()->campus;
        Cache::forget("all_award_names_{$campus->value}");

        if ($request->has('name')) {
            $request->merge([
                'name' => trim($request->input('name'))
            ]);
        }

        $changes = $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                    'min:3',
                    function ($attribute, $value, $fail) use ($campus, $award) {
                        $eventIds = $award->events()->pluck('events.id');

                        $exists = Award::where('name', $value)
                            ->where('campus', $campus->value)
                            ->where('id', '!=', $award->id) // ยกเว้นตัวเอง
                            ->whereHas('events', function ($q) use ($eventIds) {
                                $q->whereIn('events.id', $eventIds);
                            })
                            ->exists();

                        if ($exists) {
                            $fail('ชื่อหมวดรางวัลซ้ำ! มีชื่อนี้อยู่แล้วในรอบการสมัครเดียวกัน');
                        }
                    }
                ],
                'application_document' => ['mimes:pdf', 'max:10240', 'file'],
                'requirements' => ['nullable', 'array'],

                'requirements.*.id' => ['required_with:requirements', 'string', 'max:50', 'distinct'],
                'requirements.*.name' => ['required_with:requirements', 'string', 'max:255'],
                'requirements.*.required' => ['required_with:requirements', 'boolean'],
            ],
            [
                'name.required' => "โปรดกรอกชื่อหมวดรางวัล",
                'name.min' => "โปรดใส่ชื่อหมวดรางวัลอย่างน้อย 3 ตัวอักษร",
                'application_document' => "โปรดอัปโหลดเอกสารที่ถูกต้องตามข้อกำหนด",
                'requirements.*.required' => "โปรดกรอกเอกสารเพิ่มเติมให้ถูกต้อง"
            ]
        );

        $changes['requirements'] = collect($request->input('requirements', []))
            ->map(function ($req) {
                $req['required'] = (bool)$req['required'];
                return $req;
            })
            ->values()
            ->toArray();

        $award->update($changes);

        $file = $request->file('application_document');
        if ($file) {
            Storage::disk('s3')->delete($award->form_path);
            $uploadRequest = new Request();
            $uploadRequest->merge([
                'folder' => $campus->value,
            ]);

            $uploadRequest->files->set('file', $file);
            $path = MinioController::uploadFile($uploadRequest);

            $award->form_path = $path;
            $award->save();
        }

        return redirect()->route('awards.index')->with('success');
    }

    public function destroy(Award $award)
    {
        Gate::authorize('delete', $award);

        $campus = Auth::user()->campus;
        Cache::forget("all_award_names_{$campus->value}");

        $award->delete();
        return redirect()->route('awards.index');
    }
}
