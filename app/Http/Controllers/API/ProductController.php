<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\ProductResource;
use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Get All Product
     *
     * @return mixed
     */
    public function getProducts(): Response
    {
        try {

            $products = Product::with(['parent_category', 'child_category','sizes', 'media', 'variants'])->where('status', true)->get();

            $data = ProductResource::collection($products);

            return $this->sendResponseOk("Products", $data);
            
        } catch (Exception $exception) {
            return $this->sendExceptionError($exception);
        }
    }
}
