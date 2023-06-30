<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    //
    function get_all_roles()
    {
        //$roles = Role::all();
        $roles = Role::skip(1)->take(PHP_INT_MAX)->get();
        $response = [
                'status' => 'success',
                'events' => $roles
            ];
             return response()->json($response, 200);
    }
}
