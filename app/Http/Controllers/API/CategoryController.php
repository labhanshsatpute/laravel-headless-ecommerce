<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    /**
     * Get All Categories
     *
     * @return mixed
     */
    public function getCategories(): Response
    {
        try {

            $categories = Category::where('status', true)->orderBy('name')->get();

            return response([
                'status' => true,
                'message' => "Category successfully fetched",
                'data' => $categories
            ], 200);
            
        } catch (Exception $exception) {
            return response([
                'status' => true,
                'message' => "Category successfully fetched",
                'data' => $categories
            ], 500);
        }
    }
}
