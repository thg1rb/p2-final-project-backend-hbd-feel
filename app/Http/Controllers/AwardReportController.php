<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class AwardReportController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Award::class);
        $targetYear = $request->year;
        $targetSemester = $request->semester;
        $query = User::whereHas('awards.events', function ($q) use ($targetYear, $targetSemester) {

            if ($targetYear != "") {
                $q->where('academic_year', $targetYear);
            }

            if ($targetSemester != "") {
                $q->where('semester', $targetSemester);
            }
        })
            ->with('awards.events');

        if ($request->input('export') == 'csv') {
            $usersAll = $query->get();

            return $this->exportCsv($usersAll);
        }
        $users = $query->paginate(5)
            ->appends(request()->all());

        $allYears = Event::select('academic_year')
            ->distinct()
            ->orderBy('academic_year', 'desc')
            ->pluck('academic_year');

        $allSemesters = Event::select('semester')
            ->distinct()
            ->orderBy('semester', 'asc')
            ->pluck('semester');


        $awards = Award::query()
            ->whereHas('events', function ($q) use ($targetYear, $targetSemester) {
                if ($targetYear) $q->where('academic_year', $targetYear);
                if ($targetSemester) $q->where('semester', $targetSemester);
            })
            ->withCount('users')
            ->get();
        $awardStats = $awards->groupBy('name')->map(function ($group) {
            return $group->sum('users_count');
        });

        return view("report.award-report", compact('users', 'allYears', 'allSemesters', 'targetSemester', 'targetYear', 'awardStats'));
    }

    private function exportCsv($users)
    {
        $fileName = 'award-report.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($users) {
            $file = fopen('php://output', 'w');

            fputcsv($file, ['ชื่อนักเรียน', 'รายละเอียดรางวัล']);

            foreach ($users as $user) {

                $awardNames = $user->awards->pluck('name')->join(', ');

                fputcsv($file, [
                    $user->name,
                    $awardNames
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
