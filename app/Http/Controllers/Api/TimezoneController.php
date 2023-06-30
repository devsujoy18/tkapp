<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Timezone;

class TimezoneController extends Controller
{
    function get_all_timezone()
    {
        $timezones = Timezone::all();
        
        $response = [
                'status' => 'success',
                'timezones' => $timezones
            ];
             return response()->json($response, 200);
    }
}
