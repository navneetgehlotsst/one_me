<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GoodWillToken;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Traits\ApiResponser;


class GoodWillTokenController extends Controller
{
    use ApiResponser;
    public function GoodWillCreate(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'bussiness_id' => 'required|string|max:100',
                'token_amount' => 'required|string|max:100',
                'comment' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $GiftToken = GoodWillToken::create(
                [
                    'bussiness_id' => $data['bussiness_id'],
                    'token_amount' => $data['token_amount'],
                    'comment' => $data['comment'],
                    'createdby' => $userId,
                ]
            );
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($GiftToken, "Gift Token creted Succesfully", 200);
    }

    public function GoodWillList(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'status' => 'required|in:Inactive,Active,Pending',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $status = $request->status;
            $user = JWTAuth::parseToken()->authenticate();
            $userId = $user->id;
            $query = GoodWillToken::where('createdby',$userId)->where('status',$status);
            $GiftTokenList = $query->paginate(10);
            $GiftTokencount = $query->count();
            if ($GiftTokencount == '0') {
                return $this->errorResponse('Good Will Token not found', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($GiftTokenList, "Good Will Token data retrieved", 200);
    }
}
