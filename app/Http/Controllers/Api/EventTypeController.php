<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventType;

class EventTypeController extends Controller
{
    //
    function get_all_event_types()
    {
        $event_types = EventType::all();
        
        $response = [
                'status' => 'success',
                'event_types' => $event_types
            ];
             return response()->json($response, 200);
    }
}
