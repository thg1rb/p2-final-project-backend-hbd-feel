<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        $awards = Award::query()
            ->where('awards.campus', $user->campus)
            ->whereHas('events', function ($q) use ($user) {
                $q->where('status', 'OPENED')
                    ->where('campus', $user->campus);
            })
            ->get(['id', 'name', 'form_path', 'requirements']);

        return response()->json($awards);
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
        return response()->json(Award::findOrFail($id, ['id', 'name', 'form_path', 'requirements']));
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
