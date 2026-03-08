<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Application;
use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EndEventController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search', '');
        $status = $request->query('status', '');

        // จำลอง Logic การดึงข้อมูลเหมือนใน load function
        $query = Application::with(['user.faculty', 'user.department', 'award']);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('firstName', 'like', "%{$search}%")
                    ->orWhere('student_id', 'like', "%{$search}%");
            });
        }

        $applications = $query->paginate(10);

        // ดึง Stats (totalInprogress)
        $totalInprogress = Application::where('status', '!=', 'REJECTED')
            ->where('level', '!=', 6)
            ->count();

        $event = Event::where('status', Status::OPENED)
            ->where('campus', Auth::user()->campus)
            ->first();
        Log::info($event);

        $total = Application::count();

        return view('end-event.index', [
            'applications' => $applications,
            'stats' => [
                'total' => $total,
                'totalInprogress' => $totalInprogress
            ],
            // 'user' => Auth::user(),
            'hasParams' => $request->hasAny(['search', 'status']),
            'event' => $event
        ]);
    }

    public function uploadEvent(Request $request)
    {
        // 1. ลองข้าม validation ไปก่อนเพื่อเช็กว่าไฟล์มาไหม
        if (!$request->hasFile('document')) {
            dd('Laravel มองไม่เห็นไฟล์ document ครับ เช็กขนาดไฟล์ใน php.ini ดูนะ');
        }

        try {
            $file = $request->file('document');

            // 2. ระบุโฟลเดอร์ให้ชัดเจนแบบ uploadFile ที่ทำสำเร็จ
            $path = $file->store('event', 's3');

            Log::info('Path generated: ' . ($path ?: 'EMPTY'));

            if (!$path) {
                throw new \Exception('S3 Store returned empty path');
            }

            // 3. อัปเดต Database
            $event = Event::findOrFail($request->event_id);
            $event->update([
                'status' => Status::CLOSED,
                'path' => $path // มั่นใจว่าคอลัมน์ใน DB ชื่อ path นะครับ
            ]);

            return back()->with('success', 'อัปโหลดเรียบร้อย');
        } catch (\Exception $e) {
            Log::error('Upload Error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function exportPdf()
    {
        $applications = Application::with(['user.faculty', 'user.department', 'award', 'event'])
            ->where('status', 'APPROVED') // หรือเงื่อนไขที่ต้องการ
            ->get();

        if ($applications->isEmpty()) {
            return back()->with('error', 'ไม่พบข้อมูลนิสิตที่ได้รับรางวัล');
        }

        $firstApp = $applications->first();
        $userName = Auth::user()->name;

        // เตรียมข้อมูลสถิติ (Logic เดียวกับ Svelte)
        $stats = [
            'conduct' => $applications->filter(fn($a) => str_contains($a->award->name, 'ประพฤติ'))->count(),
            'activity' => $applications->filter(fn($a) => str_contains($a->award->name, 'กิจกรรม'))->count(),
            'innovation' => $applications->filter(fn($a) => str_contains($a->award->name, 'นวัตกรรม'))->count(),
        ];

        // จัดกลุ่มนิสิตตามรางวัล (สำหรับหน้าภาคผนวก)
        $groupedApps = $applications->groupBy('award.name');

        $pdf = Pdf::loadView('pdf.student_report', [
            'applications' => $applications,
            'firstApp' => $firstApp,
            'userName' => $userName,
            'stats' => $stats,
            'groupedApps' => $groupedApps,
            'today' => Carbon::now(),
        ]);

        // ตั้งค่า Font ภาษาไทย
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('student_report.pdf');
    }
}
