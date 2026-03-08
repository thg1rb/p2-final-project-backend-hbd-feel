<?php

namespace App\Http\Controllers;

use App\Enums\AwardType;
use App\Enums\Status;
use App\Models\Application;
use App\Models\Approval;
use App\Models\Award;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AwardReportController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Award::class);
        $targetYear = $request->year;
        $targetSemester = $request->semester;
        $search = $request->search;
        $awardType = $request->type;

        $query = Application::query();

        if ($targetYear != "" || $targetSemester != "") {
            $query->whereHas('event', function ($q) use ($targetYear, $targetSemester) {
                if ($targetYear != "")
                    $q->where('academic_year', $targetYear);
                if ($targetSemester != "")
                    $q->where('semester', $targetSemester);
            });
        }

        Log::info("AWARD TYPE: " . $awardType);

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

        $allYears = Event::distinct()->orderBy('academic_year', 'desc')->pluck('academic_year');
        $allSemesters = Event::distinct()->orderBy('semester', 'asc')->pluck('semester');

        $awardStats = Award::whereHas('events', function ($q) use ($targetYear, $targetSemester) {
            if ($targetYear)
                $q->where('academic_year', $targetYear);
            if ($targetSemester)
                $q->where('semester', $targetSemester);
        })
            ->withCount([
                'applications' => function ($q) use ($targetYear, $targetSemester) {
                    $q->whereHas('event', function ($sq) use ($targetYear, $targetSemester) {
                        if ($targetYear)
                            $sq->where('academic_year', $targetYear);
                        if ($targetSemester)
                            $sq->where('semester', $targetSemester);
                    });
                }
            ])
            ->get()
            ->groupBy('name')
            ->map(fn($group) => $group->sum('applications_count'));

        $event = Event::where('status', Status::OPENED)
            ->where('campus', Auth::user()->campus)
            ->first();

        return view("report.index", [
            'applications' => $applications,
            'allYears' => $allYears,
            'allSemesters' => $allSemesters,
            'targetSemester' => $targetSemester,
            'targetYear' => $targetYear,
            'awardStats' => $awardStats,
            'event' => $event,
        ]);
    }

    private function exportCsv($users)
    {
        $fileName = 'award-report.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['ชื่อนักเรียน', 'สกุลนักเรียน', 'รายละเอียดรางวัล']);

            foreach ($users as $user) {

                $awardNames = $user->awards->pluck('name')->join(', ');

                fputcsv($file, [
                    $user->firstName,
                    $user->lastName,
                    $awardNames
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
