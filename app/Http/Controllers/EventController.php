<?php

namespace App\Http\Controllers;

use App\Http\Requests\EventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Event::query();

        // Search (Input) Filter
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Status (Selection) Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Sorting Logic (3-Click Cycle: ASC -> DSC -> RESET)
        $allowedSorts = ['academic_year', 'semester', 'name'];
        if ($request->filled(['sort_by', 'sort_direction']) && in_array($request->sort_by, $allowedSorts)) {
            $query->orderBy($request->sort_by, $request->sort_direction);
        } else {
            $query->latest('id'); // Default sort
        }

        $events = $query->paginate(5)->withQueryString();

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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

        return Redirect::route('events.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('events.show', [
            'event' => $event,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
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

        return Redirect::route('events.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
