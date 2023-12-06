<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\GiftToken;
use App\Models\TokenHistory;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Str;
use App\Http\Traits\ApiResponser;


class GiftTokenController extends Controller
{
    use ApiResponser;
    public function giftCreate(Request $request){
        try {
            $data = $request->all();
            $data['token_code'] = Str::random(10);
            $validator = Validator::make($data, [
                'bussiness_id' => 'required|string|max:100',
                'token_amount' => 'required|string|max:100',
                'token_code' => 'required|unique:gift_token,token_code',
                'token_validaty' => 'required|date',
                'comment' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $user = JWTAuth::parseToken()->authenticate();
            $userID = $user->id;
            $GiftToken = GiftToken::create(
                [
                    'bussiness_id' => $data['bussiness_id'],
                    'token_amount' => $data['token_amount'],
                    'token_validaty' => $data['token_validaty'],
                    'token_code' => $data['token_code'],
                    'comment' => $data['comment'],
                    'createdby' => $userID
                ]
            );
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($GiftToken, "Gift Token creted Sussecfully", 200);
    }

    public function giftList(Request $request){
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
            $query = GiftToken::where('createdby',$userId)->where('status',$status)->where('hide_token','0');
            $GiftTokenList = $query->paginate(10);
            $GiftTokencount = $query->count();
            if ($GiftTokencount == '0') {
                return $this->errorResponse('Gift Token not found', 403);
            }
            $gift['list'] = $GiftTokenList;
            $gift['count'] = $GiftTokencount;
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($gift, "Gift Token data retrieved", 200);
    }

    public function giftDetail(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $id = $request->id;
            $GiftTokenList = GiftToken::where('id',$id)->first();
            if (!$GiftTokenList) {
                return $this->errorResponse('Gift Token not found', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($GiftTokenList, "Gift Token data retrieved", 200);
    }

    public function giftDelete(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $id = $request->id;
            $GiftTokenList = GiftToken::find($id);
            if (!$GiftTokenList) {
                return $this->errorResponse('Gift Token not found', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        $GiftTokenList->delete();
        return $this->successResponse($GiftTokenList, "Gift Token Delete Succesfully", 200);
    }

    public function giftHide(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $id = $request->id;
            $GiftTokenList = GiftToken::find($id);
            if (!$GiftTokenList) {
                return $this->errorResponse('Gift Token not found', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        $GiftTokenList->hide_token = '1';
        $GiftTokenList->save();
        return $this->successResponse($GiftTokenList, "Gift Token data retrieved", 200);
    }

    public function giftShare(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'tokenid' => 'required|numeric',
                'sharedid' => 'nullable|numeric',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $tokenid = $request->tokenid;
            $sharedid = $request->sharedid;
            $GiftTokenList = GiftToken::find($tokenid);
            if (!$GiftTokenList) {
                return $this->errorResponse('Gift Token not found', 403);
            }
            if($GiftTokenList->token_shared == '1'){
                return response()->json([
                    'status' => 'error',
                    'errors' =>  '',
                    'message'=>'Gift Token Alerady Shared'
                ],403);
                return $this->sendError([], "Gift Token Alerady Shared", 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }

        if(!$sharedid){
            $GiftTokenList->update([
                'token_shared' => '1',
            ]);
        }else{
            $GiftTokenList->update([
                'shared_id' => $sharedid,
                'token_shared' => '1',
                'status' => 'Active',
            ]);
        }
        return $this->successResponse($GiftTokenList, "Gift Token Shared Succefully", 200);
    }

    public function giftRecieved(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'status' => 'required|in:Inactive,Active',
                'sharedid' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $status = $request->status;
            $userId = $request->sharedid;
            $query = GiftToken::where('shared_id',$userId)->where('status',$status)->where('hide_token','0');
            $GiftTokenList =  $query->paginate(10);
            $GiftTokencount = $query->count();
            $dataGift['list'] = $GiftTokenList;
            $dataGift['count'] = $GiftTokencount;
            if ($GiftTokencount == '0') {
                return $this->errorResponse('Gift Token not found', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($dataGift, "Gift Token Shared Succefully", 200);
    }

    public function addToken(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'token_code' => 'required|exists:gift_token',
                'sharedid' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $token_code = $request->token_code;
            $GiftTokenList = GiftToken::where('token_code',$token_code)->where('token_shared','1')->first();
            $tokenid = $GiftTokenList->id;
            if (!$GiftTokenList) {
                return $this->errorResponse('Gift Token not found', 403);
            }

            if ($GiftTokenList->shared_id != "0" ) {
                return $this->errorResponse('Gift Token Already Activated', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        $record = GiftToken::find($tokenid);
        $record->shared_id = $request->sharedid;
        $record->status = "Active";
        $record->save();
        return $this->successResponse($GiftTokenList, "Gift Token Ceated Succesfully", 200);
    }

    public function giftRecievedDetail(Request $request){
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'id' => 'required|numeric',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $id = $request->id;
            $GiftTokenList = GiftToken::where('id',$id)->first();
            $GiftTokenHistory = TokenHistory::where('token_id',$id)->get();
            if (!$GiftTokenList) {
                return $this->errorResponse('Gift Token not found', 403);
            }
            $dataGift['list'] = $GiftTokenList;
            $dataGift['histroy'] = $GiftTokenHistory;
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        return $this->successResponse($dataGift, "Gift Token data Succesfully", 200);
    }
}
