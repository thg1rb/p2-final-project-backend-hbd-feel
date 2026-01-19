<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AwardReportController extends Controller
{
    public function index(Request $request)
    {
        $targetYear = $request->year;
        $targetSemester = $request->semester;
        $users = User::whereHas('awards.events', function ($q) use ($targetYear, $targetSemester) {

            if ($targetYear != "") {
                $q->where('academic_year', $targetYear);
            }

            if ($targetSemester != "") {
                $q->where('semester', $targetSemester);
            }
        })
            ->with('awards.events')
            ->paginate(5)
            ->appends(request()->query());

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
}
