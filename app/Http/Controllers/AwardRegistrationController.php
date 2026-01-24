<?php

namespace App\Http\Controllers;

use App\Models\ActivityAwardRegistration;
use App\Models\AwardRegistration;
use App\Models\BehaviorAwardRegistration;
use App\Models\InnovationAwardRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AwardRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
}
