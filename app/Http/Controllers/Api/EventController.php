<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    public function endEvent(Request $request)
    {
        $request->validate([
            'eventId' => 'required|exists:events,id',
            'path' => 'required|string'
        ]);

        $event = Event::findOrFail($request->eventId);

        $event->update([
            'path' => $request->path,
            'status' => Status::CLOSED
        ]);

        return response()->json([
            'message' => 'Event has been closed successfully',
            'data' => $event
        ]);
    }
    
}
