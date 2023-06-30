<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;

class CountryController extends Controller
{
    //
    function get_all_country()
    {
        $countries = Country::all();
        
        $response = [
                'status' => 'success',
                'countries' => $countries
            ];
             return response()->json($response, 200);
    }
}
