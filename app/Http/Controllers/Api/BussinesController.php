<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\Offers;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Traits\ApiResponser;


class BussinesController extends Controller
{
    use ApiResponser;
    public function listBusinesses(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'featured' => 'nullable|in:1',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'orderBy' => 'nullable|in:asc,desc',
                'category' => 'nullable',
                'max_distance' => 'nullable|numeric',
                'goodwill' => 'nullable|numeric|in:1',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
                $featured = $request->featured;
                $latitude = $request->latitude;
                $longitude = $request->longitude;
                $orderBy = $request->orderBy;
                $category = $request->category;
                $goodwill = $request->goodwill;
                $catarray = explode(",",$category);

                $distanceUnit = 300;
                $query = User::select("users.*",DB::raw("6371 * acos(cos(radians(".$latitude."))*cos(radians(users.latitude)) *cos(radians(users.longitude) - radians(".$longitude."))+sin(radians(".$latitude."))*sin(radians(users.latitude))) AS distance"),'business_category.name As business_category_name');
                $query->join('business_category', 'users.category', '=', 'business_category.id');
                $query->where('users.status','active');
                $query->where('users.role','business');
                $query->havingRaw('distance < '.$distanceUnit);
                if($request->featured != ""){
                    $query->where('featured',$featured);
                }
                if($request->category != ""){
                    $query->where('category',$catarray);
                }
                if($request->goodwill == "1"){
                    $query->where('goodwill',$goodwill);
                }
                if($request->orderBy != ""){
                    $query->orderBy('distance',$orderBy);
                }
                $Businessresults = $query->paginate(10);
                $Businesscount = $query->count();
                if ($Businesscount == '0') {
                    return $this->errorResponse('Business not found', 403);
                }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        $Businessdata['List'] = $Businessresults;
        $Businessdata['count'] = $Businesscount;
        return $this->successResponse($Businessdata, "Business data retrieved", 200);
    }
    public function filterBusinesses(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'featured' => 'nullable|in:1',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'orderBy' => 'nullable|in:asc,desc',
                'category' => 'nullable',
                'max_distance' => 'nullable|numeric',
                'goodwill' => 'nullable|numeric|in:1',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $featured = $request->featured;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $orderBy = $request->orderBy;
            $category = $request->category;
            $goodwill = $request->goodwill;
            $catarray = explode(",",$category);

            if($request->max_distance != ""){
                $distanceUnit = $request->max_distance;
            }else{
                $distanceUnit = 300;
            }
            $query = User::select("users.*",DB::raw("6371 * acos(cos(radians(".$latitude."))*cos(radians(users.latitude)) *cos(radians(users.longitude) - radians(".$longitude."))+sin(radians(".$latitude."))*sin(radians(users.latitude))) AS distance"),'business_category.name As business_category_name');
            $query->join('business_category', 'users.category', '=', 'business_category.id');
            $query->where('users.status','active');
            $query->where('users.role','business');
            $query->havingRaw('distance < '.$distanceUnit);
            if($request->featured != ""){
                $query->where('featured',$featured);
            }
            if($request->category != ""){
                $query->whereIn('category',$catarray);
            }
            if($request->orderBy != ""){
                $query->orderBy('distance',$orderBy);
            }
            if($request->goodwill == "1"){
                $query->where('goodwill',$goodwill);
            }
            $Businessresults = $query->paginate(10);
            $Businesscount = $query->count();
            if ($Businesscount == "0") {
                return $this->errorResponse('Business not found', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        $Businessdata['List'] = $Businessresults;
        $Businessdata['count'] = $Businesscount;
        return $this->successResponse($Businessdata, "Business data retrieved", 200);
    }
    public function searchBusinesses(Request $request){
        try {

            $data = $request->all();
            $validator = Validator::make($data, [
                'search' => 'required',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $search = $request->search;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $distanceUnit = 300;
            $query = User::select("users.*",DB::raw("6371 * acos(cos(radians(".$latitude."))*cos(radians(users.latitude)) *cos(radians(users.longitude) - radians(".$longitude."))+sin(radians(".$latitude."))*sin(radians(users.latitude))) AS distance"),'business_category.name As business_category_name');
            $query->join('business_category', 'users.category', '=', 'business_category.id');
            $query->where('users.status','active');
            $query->where('users.role','business');
            $query->where('business_name', 'like', '%'.$search.'%');
            $query->orWhere('city', 'like', '%'.$search.'%');
            $query->havingRaw('distance < '.$distanceUnit);
            $Businessresults = $query->paginate(10);
            $Businesscount = $query->count();
            if (empty($Businessresults)) {
                return $this->errorResponse('Business not found', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        $Businessdata['List'] = $Businessresults;
        $Businessdata['count'] = $Businesscount;
        return $this->successResponse($Businessdata, "Business data retrieved", 200);
    }
    public function detailBusinesses(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'id' => 'required|numeric',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $id = $request->id;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $distanceUnit = 300;
            $query = User::select("users.*",DB::raw("6371 * acos(cos(radians(".$latitude."))*cos(radians(users.latitude)) *cos(radians(users.longitude) - radians(".$longitude."))+sin(radians(".$latitude."))*sin(radians(users.latitude))) AS distance"),'business_category.name As business_category_name');
            $query->join('business_category', 'users.category', '=', 'business_category.id');
            $query->where('users.id',$id);
            $query->havingRaw('distance < '.$distanceUnit);
            $Businessresults = $query->first();

            $offers = Offers::where('bussiness_id', '=', $id)->where('status', '=', '1')->get();


            if (!$Businessresults) {
                return $this->errorResponse('Business not found', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        $Businessdata['List'] = $Businessresults;
        $Businessdata['offers'] = $offers;
        return $this->successResponse($Businessdata, "Business data retrieved", 200);
    }
}
