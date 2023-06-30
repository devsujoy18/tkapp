<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    function get_all_categories()
    {
        $categories = Category::all();
        
        $response = [
                'status' => 'success',
                'categories' => $categories
            ];
             return response()->json($response, 200);
    }
}
