<?php

namespace App\Http\Controllers;

use App\Http\Requests\AwardRegistration\Step2\ActivityRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityAwardRegistration;
use App\Models\AwardRegistration;
use App\Models\BehaviorAwardRegistration;
use App\Models\InnovationAwardRegistration;
use Illuminate\Support\Facades\Auth;

class AwardRegistrationController extends Controller
{
    public function create(Request $request)
    {
        $step = (int) $request->query('step', 1);

        if (!in_array($step, [1, 2, 3])) {
            abort(404);
        }

        return view("award-registrations.steps.step{$step}", compact('step'));
    }

    public function store(Request $request)
    {
        $step = (int) $request->query('step', 1);

        if (!in_array($step, [1, 2, 3])) {
            abort(404);
        }

        match ($step) {
            1 => $this->storeStep1($request),
            2 => $this->storeStep2($request),
            3 => null,
        };

        if ($request->input('action') === 'back') {
            return redirect()->route('award-registrations.create', ['step' => $step - 1]);
        }

        if ($step < 3) {
            return redirect()->route('award-registrations.create', ['step' => $step + 1]);
        }

        session()->forget('award_registration');
        return redirect()->route('award-registrations.index');
    }

    private function storeStep1(Request $request)
    {
        $oldType = session('award_registration.step1.award_type');

        if ($oldType && $oldType !== $request->award_type) {
            session()->forget('award_registration');
        }

        session([
            'award_registration.step1' => [
                'award_type' => $request->award_type,
            ]
        ]);
    }

    private function storeStep2(Request $request)
    {
        $type = session('award_registration.step1.award_type');
        $action = $request->input('action', 'next');

        if ($action === 'back') {
            session([
                'award_registration.step2' => array_merge(
                    session('award_registration.step2', []),
                    $request->except('documents')
                )
            ]);
            return;
        }

        $data = match ($type) {
            'activity' => app(ActivityRequest::class)->validated(),
            'good-conduct' => $request->validate([
                'approver' => 'required|string|max:255',
            ]),
            'innovation' => $request->validate([
                'award' => 'required|string|max:255',
            ]),
        };

        $documents = session('award_registration.step2.documents', []);

        if ($request->hasFile('documents')) {

            foreach ($documents as $oldPath) {
                Storage::delete($oldPath);
            }

            $documents = [];

            foreach ($request->file('documents') as $file) {
                $filename = now()->timestamp . '_' . $file->getClientOriginalName();

                $documents[] = $file->storeAs(
                    'award-registrations',
                    $filename
                );
            }
        }

        session([
            'award_registration.step2' => [
                ...$data,
                'documents' => $documents,
            ]
        ]);

    public function index(Request $request)
    {
        // เริ่มต้น Query โดยโหลดความสัมพันธ์ 'awardable' (ลูก) และ 'award' (ข้อมูลรางวัล) มาด้วย
        $baseQuery = AwardRegistration::query();

        // ตรวจสอบสิทธิ์
        if (auth::check() && auth()->user()->role !== 'admin') {
            // ถ้าไม่ใช่ Admin ให้ดึงเฉพาะข้อมูลที่ user_id ตรงกับคนที่ Login อยู่
            $baseQuery->where('user_id', auth()->id());
        }
        else $baseQuery->where('user_id', 1);


        $allStats = $baseQuery->get(['status']);


        // ทำ Pagination เพื่อไม่ให้โหลดข้อมูลหนักเกินไป (เช่น หน้าละ 15 รายการ)
        $registrations = (clone $baseQuery)
            ->with(['awardable', 'award', 'event'])
            ->latest()
            ->paginate(5);

        return view('award-registrations.index', compact('registrations', 'allStats'));
    }

    /**
     * Display the specified resource.
     */
    public function show(AwardRegistration $awardRegistration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AwardRegistration $awardRegistration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AwardRegistration $awardRegistration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AwardRegistration $awardRegistration)
    {
        //
    }
