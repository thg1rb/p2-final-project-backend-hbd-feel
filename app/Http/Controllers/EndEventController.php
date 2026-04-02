<?php

namespace App\Http\Controllers;

use App\Enums\ApprovalStatus;
use App\Enums\RoleLevel;
use App\Enums\Status;
use App\Models\Application;
use App\Models\Event;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class EndEventController extends Controller
{
    public function index(Request $request)
    {
        // ดึง Stats (totalInprogress)
        $totalInprogress = Application::where('status', '!=', 'REJECTED')
            ->where('level', '!=', RoleLevel::BOARD)
            ->count();

        $event = Event::where('status', Status::OPENED)
            ->where('campus', Auth::user()->campus)
            ->first();

        $total = Application::count();

        return view('end-event.index', [
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

            Log::info('1: Path generated: ' . ($path ?: 'EMPTY'));

            if (!$path) {
                Log::info('2: inner if -> ' . $path);
                throw new \Exception('S3 Store returned empty path');
            }

            Log::info('3: after if -> ' . $path);

            // 3. อัปเดต Database
            $event = Event::findOrFail($request->event_id);

            Log::info('4: after findOrFail -> ' . $request->input(''));
            $event->update([
                'status' => Status::CLOSED,
                'path' => $path // มั่นใจว่าคอลัมน์ใน DB ชื่อ path นะครับ
            ]);

            // ===========================================
            $campus = $event->campus->value ?? $event->campus;
            $year = $event->academic_year;
            $semester = $event->semester;

            Cache::forget("winner_years_{$campus}");

            Cache::forget("winner_semesters_{$campus}_{$year}");

            Cache::forget("winner_results_{$campus}_{$year}_{$semester}");
            // ===========================================

            return back()->with('success', 'อัปโหลดเรียบร้อย');
        } catch (\Exception $e) {
            Log::error('Upload Error: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function exportPdf(Request $request)
    {

        Log::info("TEST");
        $campus = Auth::user()->campus;

        $signerName = $request->query('signer_name', '....................');

        $event = Event::where('status', Status::OPENED)
            ->where('campus', Auth::user()->campus)
            ->first();

        $year = $event->academic_year;
        $semester = $event->semester;

        $applications = Application::with([
            'award',
            'user',
            'user.faculty',
            'event'
        ])
            ->where('status', ApprovalStatus::APPROVED)
            ->where('level', RoleLevel::BOARD)
            ->whereHas('award', function ($q) use ($campus) {
                $q->where('campus', $campus);
            })
            ->whereHas('event', function ($q) use ($year, $semester, $campus) {
                $q->where('academic_year', $year)
                    ->where('semester', $semester)
                    ->where('campus', $campus);
            })
            ->get();

        Log::info($applications);

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
            'signerName' => $signerName,
            'stats' => $stats,
            'groupedApps' => $groupedApps,
            'today' => Carbon::now(),
        ]);

        // ตั้งค่า Font ภาษาไทย
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('student_report.pdf');
    }
}
