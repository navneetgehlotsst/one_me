<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Traits\ApiResponser;


class BussinesCategoryController extends Controller
{
    use ApiResponser;

    public function category(Request $request){
        try {
            $categories = Category::all();
            if (empty($categories)) {
                return $this->errorResponse('Category not found', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return response()->json([
            'status' => 'success',
            'data' => $categories,
            'message'=>'Category data retrieved.'
        ],200);

        return $this->successResponse($categories, "Category data retrieved", 200);
    }
}
