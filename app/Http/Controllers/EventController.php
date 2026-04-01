<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Gate::authorize('view-any', Event::class);
        $query = Event::query();

        // Campus Filter - users only see events from their own campus
        $query->where('campus', Auth::user()->campus);

        // Search (Input) Filter
        if ($request->filled('search')) {
            $query->where('academic_year', 'like', '%' . $request->search . '%');
        }

        // Status (Selection) Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting Logic (Multiple Sort)
        $sorts = $request->input('sorts', []);
        $allowedSorts = ['academic_year', 'semester', 'start_date', 'end_date'];
        if (is_array($sorts) && !empty($sorts)) {
            foreach ($sorts as $column => $direction) {
                // Validate column and direction
                if (in_array($column, $allowedSorts) && in_array(strtolower($direction), ['asc', 'desc'])) {
                    $query->orderBy($column, $direction);
                }
            }
        } else {
            // Default sort by academic_year and semester in order.
            $query->orderBy('academic_year', 'desc')
                ->orderBy('semester', 'desc');
        }

        $events = $query->paginate(10)->withQueryString();

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Event::class);
        return view('events.create', [
            'event' => new Event(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EventRequest $request)
    {
        Event::create($request->validated());

        $this->clearEventCache(
            Auth::user()->campus,
            $request->academic_year,
            $request->semester
        );

        return Redirect::route('events.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        Gate::authorize('view', $event);
        return view('events.show', [
            'event' => $event,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        Gate::authorize('update', $event);
        return view('events.edit', [
            'event' => $event,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EventRequest $request, Event $event)
    {
        $event->update($request->validated());

        $this->clearEventCache(
            Auth::user()->campus,
            $event->academic_year,
            $event->semester
        );

        return Redirect::route('events.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        Gate::authorize('delete', $event);

        $campus = Auth::user()->campus;
        $year = $event->academic_year;
        $semester = $event->semester;

        $event->delete();

        $this->clearEventCache($campus, $year, $semester);

        return Redirect::route('events.index');
    }

    private function clearEventCache($campus, $year = null, $semester = null)
    {
        $campusValue = $campus->value ?? $campus;

        // ล้าง Cache หน้า Admin
        Cache::forget("all_years_{$campusValue}");
        Cache::forget("all_semesters_{$campusValue}");
        Cache::forget("active_event_{$campusValue}");

        Cache::forget("years_campus_{$campusValue}");
        if ($year) {
            Cache::forget("semesters_campus_{$campusValue}_year_{$year}");
        }

        Cache::forget("winner_years_{$campusValue}");
        Log::info("forget winner_years_{$campusValue}");

        if ($year && $semester) {
            Cache::forget("winner_semesters_{$campusValue}_{$year}");
            Cache::forget("winner_results_{$campusValue}_{$year}_{$semester}");
        }
    }
}
