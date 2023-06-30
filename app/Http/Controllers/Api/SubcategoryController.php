<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subcategory;

class SubcategoryController extends Controller
{
    function get_all_subcategories($cat_id)
    {
        $sub_categories = Subcategory::where('category_id', $cat_id)->get();
        
        $response = [
                'status' => 'success',
                'sub_categories' => $sub_categories
            ];
        return response()->json($response, 200);
    }
}
