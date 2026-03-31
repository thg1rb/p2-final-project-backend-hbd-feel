<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AwardController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view-any', Award::class);

        // 1. ดึงปีการศึกษาที่มีทั้งหมด (Unique) เพื่อแสดงใน Dropdown แรก
        $years = Event::distinct()
            ->where('campus', Auth::user()->campus)
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        // 2. ดึงภาคเรียน โดยอ้างอิงจากปีการศึกษาที่เลือก (ถ้าไม่ได้เลือก ให้ดึงทั้งหมดที่มีในระบบ)
        $semesterQuery = Event::distinct()->where('campus', Auth::user()->campus);

        if ($request->filled('academic_year')) {
            $semesterQuery->where('academic_year', $request->academic_year);
        }

        $semesters = $semesterQuery->orderBy('semester', 'asc')->pluck('semester');

        // 3. กรองข้อมูล Award
        $query = Award::query()
            ->where('campus', Auth::user()->campus);

        // กรองตามความสัมพันธ์ของ Event (Year & Semester)
        if ($request->filled('academic_year') || $request->filled('semester')) {
            $query->whereHas('events', function ($q) use ($request) {
                if ($request->filled('academic_year')) {
                    $q->where('academic_year', $request->academic_year);
                }
                if ($request->filled('semester')) {
                    $q->where('semester', $request->semester);
                }
            });
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $awards = $query->with('events')->paginate(10)->withQueryString();

        $event = Event::where("campus", Auth::user()->campus->value)
            ->where("status", "OPENED")
            ->first();

        return view('awards.index', compact('awards', 'event', 'years', 'semesters'));
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

        $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'min:3'],
                //            'reward' => ['required', 'numeric', 'min:0', 'max:1000000', 'regex:/^\d+(\.\d{1,2})?$/'],
                'application_document' => ['required', 'mimes:pdf', 'max:10240', 'file'],
                'requirements' => ['nullable', 'array'],

                'requirements.*.id' => ['required_with:requirements', 'string', 'max:50', 'distinct'],
                'requirements.*.name' => ['required_with:requirements', 'string', 'max:255'],
                'requirements.*.required' => ['required_with:requirements', 'boolean'],
            ],
            [
                'name.required' => "โปรดกรอกชื่อหมวดรางวัล",
                'name.min' => "โปรดใส่ชื่อหมวดรางวัลอย่างน้อย 3 ตัวอักษร",
                //                'reward.required' => "โปรดกรอกจำนวนรางวัล (บาท)",
                //                'reward.min' => 'จำนวนเงินรางวัลต้องไม่เป็นเลขติดลบ',
                //                'reward' => 'โปรดกรอกจำนวนเงินรางวัลให้ถูกต้อง',
                'application_document.required' => "โปรดอัปโหลดเอกสารใบสมัคร",
                'application_document' => "โปรดอัปโหลดเอกสารที่ถูกต้องตามข้อกำหนด",
                'requirements.*.required' => "โปรดกรอกเอกสารเพิ่มเติมให้ถูกต้อง"
            ]
        );

        $file = $request->file('application_document');
        $uploadRequest = new Request();
        $uploadRequest->merge([
            'folder' => Auth::user()->campus->value,
        ]);

        $uploadRequest->files->set('file', $file);
        $path = MinioController::uploadFile($uploadRequest);
        $award = new Award();
        $award->name = $request->input('name');
        //        $award->reward = $request->input('reward');
        $award->form_path = $path;
        $award->campus = Auth::getUser()->campus->value;
        $requirements = collect($request->input('requirements', []))
            ->filter(fn($field) => !empty($field['name']))
            ->map(fn($field) => [
                'id' => $field['id'],
                'name' => $field['name'],
                'required' => (bool) $field['required'],
            ])
            ->values()
            ->toArray();
        $award->requirements = $requirements;
        $award->save();

        $activeEvent = Event::where("campus", Auth::user()->campus->value)
            ->where("status", "OPENED")
            ->first();

        if (!$activeEvent) {
            return redirect()->back()->withErrors(['error' => 'ไม่พบช่วงเวลาการสมัครที่เปิดอยู่']);
        }
        $award->events()->attach($activeEvent->id);

        return redirect()->route('awards.index');
    }

    public function edit(Award $award)
    {
        Gate::authorize('update', $award);
        return view('awards.edit', ['award' => $award]);
    }

    public function update(Request $request, Award $award)
    {
        Gate::authorize('update', $award);

        $campus = Auth::user()->campus;
        Cache::forget("all_award_names_{$campus->value}");

        $changes = $request->validate(
            [
                'name' => ['required', 'string', 'max:255', 'min:3'],
                //            'reward' => ['required', 'numeric', 'min:0', 'max:1000000', 'regex:/^\d+(\.\d{1,2})?$/'],
                'application_document' => ['mimes:pdf', 'max:10240', 'file'],
                'requirements' => ['nullable', 'array'],

                'requirements.*.id' => ['required_with:requirements', 'string', 'max:50', 'distinct'],
                'requirements.*.name' => ['required_with:requirements', 'string', 'max:255'],
                'requirements.*.required' => ['required_with:requirements', 'boolean'],
            ],
            [
                'name.required' => "โปรดกรอกชื่อหมวดรางวัล",
                'name.min' => "โปรดใส่ชื่อหมวดรางวัลอย่างน้อย 3 ตัวอักษร",
                //                'reward.required' => "โปรดกรอกจำนวนรางวัล (บาท)",
                //                'reward.min' => 'จำนวนเงินรางวัลต้องไม่เป็นเลขติดลบ',
                //                'reward' => 'โปรดกรอกจำนวนเงินรางวัลให้ถูกต้อง'
                'application_document' => "โปรดอัปโหลดเอกสารที่ถูกต้องตามข้อกำหนด",
                'requirements.*.required' => "โปรดกรอกเอกสารเพิ่มเติมให้ถูกต้อง"
            ]
        );

        $changes['requirements'] = collect($request->input('requirements', []))
            ->map(function ($req) {
                $req['required'] = (bool) $req['required'];
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
                'folder' => Auth::user()->campus->value,
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
