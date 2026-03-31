<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Models\Application;
use App\Models\Approval;
use App\Models\Award;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;

class AwardReportController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Award::class);

        $adminCampus = Auth::user()->campus;

        $targetYear = $request->year;
        $targetSemester = $request->semester;
        $targetAward = $request->targetAward;
        $search = $request->search;
        $awardType = $request->type;

        $query = Application::query();

        $query->whereHas('user', function ($q) use ($adminCampus) {
            $q->where('campus', $adminCampus);
        })->whereHas('award', function ($q) use ($adminCampus) {
            $q->where('campus', $adminCampus);
        });

        if ($targetYear != "" || $targetSemester != "") {
            $query->whereHas('event', function ($q) use ($targetYear, $targetSemester) {
                if ($targetYear != "")
                    $q->where('academic_year', $targetYear);
                if ($targetSemester != "")
                    $q->where('semester', $targetSemester);
            });
        }


        if ($awardType != "") {
            $query->whereHas('award', function ($q) use ($awardType) {
                $q->where('name', $awardType);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQ) use ($search) {
                    $userQ->where('firstName', 'LIKE', "%{$search}%")
                        ->orWhere('lastName', 'LIKE', "%{$search}%")
                        ->orWhere('student_id', 'LIKE', "%{$search}%");
                })
                    ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }

        $query->with(['user', 'award', 'event']);

        if ($request->input('export') == 'csv') {
            return $this->exportCsv($query->get());
        }

        $applications = $query->paginate(10)->appends($request->all());

        $cacheKey = "all_years_{$adminCampus->value}";

        $allYears = Cache::remember($cacheKey, now()->addDay(), function () use ($adminCampus) {
            return Event::where('campus', $adminCampus)
                ->distinct()
                ->orderBy('academic_year', 'desc')
                ->pluck('academic_year');
        });


        $allSemesters = Cache::remember("all_semesters_{$adminCampus->value}", now()->addDay(), function () use ($adminCampus) {
            return Event::where('campus', $adminCampus)
                ->distinct()
                ->orderBy('semester', 'asc')
                ->pluck('semester');
        });

        $event = Cache::remember("active_event_{$adminCampus->value}", now()->addMinutes(5), function () use ($adminCampus) {
            return Event::where('status', Status::OPENED)
                ->where('campus', $adminCampus)
                ->first();
        });

        $allAwardNames = Cache::remember("all_award_names_{$adminCampus->value}", now()->addDay(), function () use ($adminCampus) {
            return Award::where('campus', $adminCampus)
                ->distinct()
                ->orderBy('name', 'asc')
                ->pluck('name');
        });

        return view("report.index", [
            'applications' => $applications,
            'allYears' => $allYears,
            'allSemesters' => $allSemesters,
            'targetSemester' => $targetSemester,
            'targetYear' => $targetYear,
            'targetAward' => $targetAward,
            'awards' => $allAwardNames,
            'event' => $event,
        ]);
    }

    private function exportCsv($applications) // เปลี่ยนชื่อตัวแปรให้ไม่งง
    {
        $fileName = 'award-report-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8", // เพิ่ม charset
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($applications) {
            $file = fopen('php://output', 'w');

            // ใส่ BOM เพื่อให้ Excel เปิดภาษาไทยได้ถูกต้อง
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // หัวตาราง
            fputcsv($file, ['รหัสนักศึกษา', 'ชื่อ', 'นามสกุล', 'รางวัล', 'ปีการศึกษา', 'เทอม', 'ชั้นปี']);

            foreach ($applications as $app) {
                fputcsv($file, [
                    $app->user->student_id ?? '-',
                    $app->user->firstName,
                    $app->user->lastName,
                    $app->award->name ?? '-',
                    $app->event->academic_year ?? '-',
                    $app->event->semester ?? '-',
                    $app->event->year,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show($id)
    {
        $application = Application::with(['user.department', 'award', 'event'])
            ->findOrFail($id);

        $approvals = Approval::where('application_id', $id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        $event = Event::where('status', Status::OPENED)
            ->where('campus', Auth::user()->campus)
            ->first();

        $headDeptApproval = $approvals->firstWhere('user.role', 'DEPT_HEAD');

        return view('report.show', compact('application', 'approvals', 'headDeptApproval', 'event'));
    }

    public function edit($id)
    {
        $application = Application::with(['user.department', 'award', 'event'])
            ->findOrFail($id);
        $awards = Award::all();
        return view('report.edit', compact('application', 'awards'));
    }

    public function update(Request $request, Application $application)
    {
        $request->validate([
            'award_id' => 'required|exists:awards,id',
        ]);

        $application->update([
            'award_id' => $request->award_id
        ]);

        return redirect()->route('report.show', $application)
            ->with('success', 'เปลี่ยนประเภทรางวัลสำเร็จ');
    }
}
