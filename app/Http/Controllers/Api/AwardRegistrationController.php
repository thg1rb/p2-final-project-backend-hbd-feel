<?php

namespace App\Http\Controllers\Api;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Resources\AwardRegistrationIndexResource;
use App\Http\Resources\AwardRegistrationResource;
use App\Models\AwardRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AwardRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $baseQuery = AwardRegistration::query();

        if ($request->hasHeader('Student-ID')) {
            $baseQuery->where('user_id', $request->header('Student-ID'));
        }


        $allStats = $baseQuery->get(['status']);

        $registrations = (clone $baseQuery)
            ->with(['awardable', 'award', 'event'])
            ->latest()
            ->paginate(5);

        $currentEvent = \App\Models\Event::where('status', "OPENED")
            ->first();

//        return view('award-registrations.index', compact('registrations', 'allStats','currentEvent'));
        return new AwardRegistrationIndexResource([
            'registrations' => $registrations,
            'currentEvent' => $currentEvent,
            'allStats' => $allStats,
        ]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
