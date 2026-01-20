<?php

namespace App\Http\Controllers;

use App\Models\Award;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

class MainDashboardController extends Controller
{
    public  function index()
    {
        Gate::authorize('viewAny', Award::class);
        $totalUser = User::where('role', '!=', 'admin')->count();
        $currentEvent = Event::where('status', 'OPENED')->first();

        if ($currentEvent == null) {
            $currentAwardTotal = 0;
        } else {
            $currentAwardTotal = DB::table('event_award')
                ->join('events', 'event_award.event_id', '=', 'events.id')
                ->where('events.semester', $currentEvent->semester)
                ->where('events.academic_year', $currentEvent->academic_year)
                ->count();
        }
        return view("main.index", compact('totalUser', 'currentEvent', 'currentAwardTotal'));
    }
}
