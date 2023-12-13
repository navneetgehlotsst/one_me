<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPreference;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Mail,Hash,File;
use App\Mail\ForgotPasswordMail;
use App\Mail\VerifyUserEmail;
use App\Http\Traits\ApiResponser;

class AuthController extends Controller
{
    use ApiResponser;

    public function getBaseUrl(){
        $url = url('/').'/';
        // return response()->json([
        //     'status' => 'success',
        //     'message' =>  '',
        //     'url' => $url,
        // ],200);
        try {
            return $this->successResponse($url, 'Base Url successful', 200);

        } catch (\Exception $e) {
            return $this->errorResponse('Error occurred', 500);
        }
    }

    public function getUserDetail($user_id){
        $base_url = asset('/');
        $user = User::where('id',$user_id)->first();

        if($user->email_verified_at){
            $user->email_verified_at = $user->email_verified_at;
        }else{
            $user->email_verified_at = "";
        }

        if($user->phone_verified_at){
            $user->phone_verified_at = $user->phone_verified_at;
        }else{
            $user->phone_verified_at = "";
        }

        if($user->avatar){
            $user->avatar = $base_url.$user->avatar;
        }else{
            $user->avatar = "";
        }

        $userarray = [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'full_name' => $user->full_name,
            'email' => $user->email,
            'country_code' => $user->country_code,
            'phone' => $user->phone,
            'email_verified_at' => $user->email_verified_at,
            'phone_verified_at' => $user->phone_verified_at,
            'role' => $user->role,
            'address' => $user->address,
            'area' => $user->area,
            'city' => $user->city,
            'country' => $user->country,
            'zipcode' => $user->zipcode,
            'latitude' => $user->latitude,
            'longitude' => $user->longitude,
            'preference' => $user->preference,
            'status' => $user->status,
        ];
        return $userarray;
    }


    public function sendOtp(Request $request){
        $data = $request->all();
        $is_valid_email = 1;
        if(array_key_exists('email',$data)){
            $check_email = User::where('email',$data['email'])->where('email_verified_at','!=',null)->first();
            if($check_email){
                $is_valid_email = 0;
            }
        }
        $data['is_valid_email'] = $is_valid_email;
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'is_valid_email' => 'not_in:0',
        ],[
            'is_valid_email.not_in' => 'Email is already verified.'
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }else{
            $code = rand(100000,999999);
            $date = date('Y-m-d H:i:s');
            $currentDate = strtotime($date);
            $futureDate = $currentDate+(60*5);
            $user = User::where('email',$data['email'])->first();
            if(!$user){
                $user = new User();
            }
            $user->email = $data['email'];
            $user->otp = $code;
            $user->otp_expire_time = $futureDate;
            $user->save();
            Mail::to($data['email'])->send(new VerifyUserEmail($user, $code));
            return $this->successResponse('', 'A six digits email verification code is sent to your email.Please check your email', 200);

        }
    }

    public function reSendOtp(Request $request){
        $data = $request->all();
        $is_valid_email = 1;
        if(array_key_exists('email',$data)){
            $check_email = User::where('email',$data['email'])->where('email_verified_at','!=',null)->first();
            if($check_email){
                $is_valid_email = 0;
            }
        }
        $data['is_valid_email'] = $is_valid_email;
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'is_valid_email' => 'not_in:0',
        ],[
            'is_valid_email.not_in' => 'Email is already verified.'
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }else{
            $code = rand(100000,999999);
            $date = date('Y-m-d H:i:s');
            $currentDate = strtotime($date);
            $futureDate = $currentDate+(60*5);
            $user = User::where('email',$data['email'])->first();
            $user->otp = $code;
            $user->otp_expire_time = $futureDate;
            $user->save();
            Mail::to($data['email'])->send(new VerifyUserEmail($user, $code));
            return $this->successResponse('', 'A six digits email verification code is sent to your email.Please check your email', 200);

        }
    }

    public function verifyOtp(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => "required|email",
            'otp' => "required|max:6",
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }else{
            $user = User::where('email',$data['email'])->first();
            $date = date('Y-m-d H:i:s');
            $currentTime = strtotime($date);
            if($user->otp == $data['otp']){
                if($currentTime < $user->otp_expire_time){
                    $user->otp = '';
                    $user->otp_expire_time = '';
                    $user->email_verified_at = $date;
                    $user->save();
                    return $this->successResponse('','Email verified successfully.', 200);
                }else{
                    $user->otp = '';
                    $user->otp_expire_time = '';
                    $user->save();
                    return $this->errorResponse('Otp expired..!', 404);
                }
            }else{
                return $this->errorResponse('Please enter valid Otp.', 404);
            }
        }
    }

    public function register(Request $request)
    {
        $data = $request->all();
        $check_email = 1;
        $validator = Validator::make($data, [
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|numeric|digits_between:4,12|unique:users,phone',
            'password' => 'required|min:6',
            'c_password' => 'required|same:password',
            'address' => 'sometimes|string|max:255',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }
        $user = User::updateOrCreate(
            ['email' => $data['email']],
            [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'full_name' => $data['first_name'] . ' ' . $data['last_name'],
                'password' => bcrypt($data['password']),
                'temp_password' => $data['password'],
                'phone' => $data['mobile'],
                'email' => $data['email'],
                'address' => $data['address'],
                'city' => $data['city'],
                'state' => $data['state'],
                'country' => $data['country'],
                'zipcode' => $data['zipcode'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'country_code' => $data['country_code'],
                'role' => 'user',
                'status' => 'active',
            ]
        );
        $id = $user->id;

        $datauser['user'] = $this->getUserDetail($id);
        //$datauser = $this->getUserDetail($id);

        return $this->successResponse($datauser,'User successfully registered', 422);

    }

    public function login(Request $request)
    {
        $input = $request->only('phone', 'password');
        $validator = Validator::make($input, [
            'phone' => 'required|numeric|digits_between:8,12',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }

        $user = User::where('phone',$input['phone'])->first();
        if($user){
            if(Hash::check($input['password'], $user->password)){
                if($user->status != 'active'){
                    return $this->errorResponse('You are a inactive user!pleae contact to adminstrator', 400);
                }
            }

            if($user->phone_verified_at == null){
                $data['user'] = $this->getUserDetail($user->id);
                return $this->errordataResponse($data,'Mobile Number Not Verified', 400);
            }
        }else{
            return $this->errorResponse('invalid login credentials', 400);
        }



        try{
            if(!$token = JWTAuth::attempt($input)) {
                return $this->errorResponse('invalid login credentials', 400);
            }
        }catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        $data['access_token'] = $token;
        $data['token_type'] = 'bearer';
        $data['preference'] = $user->preference;
        $data['user'] = $this->getUserDetail(auth()->user()->id);
        return $this->successResponse($data,'Login successfully.', 200);

    }

    public function refresh() {
        return $this->createNewToken(JWTAuth::refresh());
    }

    protected function createNewToken($token){
        return response()->json([
            'status' => 'success',
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user(),
            'message'=>'Token refresh successfully.'
        ],200);
    }

    public function getUser()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) {
                return $this->errorResponse('user not found', 403);
            }
        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);
        }
        if($user->avatar = ""){
            $user->avatar = $url . $user->avatar;
        }else{
            $user->avatar = "";
        }
        return $this->successResponse($user,'user data retrieved', 200);
    }

    public function forgotPassword(Request $request){
        $data = $request->all();

        $validator = Validator::make($data, [
            'email' => "required|email|exists:users",
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }else{
            $user = User::where('email',$data['email'])->first();
            $code = rand(100000,999999);
            $date = date('Y-m-d H:i:s');
            $currentDate = strtotime($date);
            $futureDate = $currentDate + (60*5);
            $user->otp = $code;
            $user->otp_expire_time = $futureDate;
            $user->save();
            $name = $user->first_name.' '.$user->last_name;
            Mail::to($data['email'])->send(new ForgotPasswordMail($user, $code));
            return $this->successResponse('','A six digits password reset code is sent to your email.Please check your email', 200);

        }
    }

    public function setForgotPassword(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|min:6',
            'otp' => 'required|numeric|min:6',
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }else{
            $date = date('Y-m-d H:i:s');
            $currentTime = strtotime($date);
            $user = User::where('email',$data['email'])->where('otp',$data['otp'])->where('otp_expire_time','>',$currentTime)->first();
            if($user){
                if(Hash::check($request->password,$user->password)){
                    return $this->errorResponse('Cannot use your old password as new password.', 400);
                }else{
                    $user->password = Hash::make($request->password);
                    $user->otp = '';
                    $user->otp_expire_time = '';
                    $user->save();
                    return $this->successResponse('','New Password set successfully.Please Login', 200);
                }

            }
            else{
                return $this->errorResponse('Otp expired or Please enter valid otp.', 400);
            }
        }
    }

    public function logout() {
        JWTAuth::parseToken()->invalidate(true);
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function resetPassword(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'old_password' => 'required',
            'new_password' => 'required|string|min:6',
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }else{
            $user = auth()->user();
            if(Hash::check($user->password, $request->new_password)) {
                return $this->errorResponse('Cannot use your old password as new password.', 400);
            }else{
                $user->password = Hash::make($request->new_password);
                $user->save();
                JWTAuth::parseToken()->invalidate(true);
                return $this->successResponse('','Password changed successfully.Please Login', 200);
            }
        }
    }

    public function updateProfile(Request $request){
        $data   =   $request->all();
        $id = auth()->user()->id;
        $validator = Validator::make($data, [
            'first_name'        =>  'required',
            'last_name'         =>  'required',
            'mobile'            =>  'required|unique:users,phone,'.$id,
            'email'             =>  'required|email|unique:users,email,'.$id,
        ],[
            'mobile.mobile_valid'  =>  'Enter a valid mobile number',
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }

        $user = User::find($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->full_name = $request->first_name.' '.$request->last_name;
        $user->phone = $request->mobile;
        $user->email = $request->email;
        $user->address = $request->address;
        $user->save();
        if($user->avatar = ""){
            $user->avatar = $url . $user->avatar;
        }else{
            $user->avatar = "";
        }
        return $this->successResponse($user,'Profile update succesfully', 200);
    }


    public function updatePreference(Request $request){
        $url = url('/').'/';
        $data   =   $request->all();
        $id = $data['id'];
        $validator = Validator::make($data, [
            'preference'        =>  'required',
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }

        $preference = $request->preference;
        $preferencearray = explode(",",$preference);

        foreach ($preferencearray as $key => $value) {
            UserPreference::create([
                'user_id' => $id,
                'preference' => $value,
            ]);
        }

        $user = User::find($id);
        $user->preference = 1;
        $user->save();
        $user->avatar = $url . $user->avatar;


        return $this->successResponse($user,'Preference added Succesfully', 200);
    }

    public function updateProfileImage(Request $request){
        $url = url('/').'/';
        $data = $request->all();
        $id = $data['id'];
        $validator = Validator::make($data, [
            'avatar'        =>  'mimes:jpeg,jpg,png|required|max:2000',
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }
        try{
            $user = User::find($id);
            $file       = $request->file('avatar');
            $filename   = time().$file->getClientOriginalName();
            $folder = 'uploads/user/';
            $path = public_path($folder);
            if(!File::exists($path)) {
                File::makeDirectory($path, $mode = 0777, true, true);
            }
            $file->move($path, $filename);
            $user->avatar   = $folder.$filename;
            $user->save();
            $user->avatar = $url . $user->avatar;
            return $this->successResponse($user,'Profile image update successfully', 200);
        }
        catch(Exception $e)
        {
            return $this->errorResponse($e->getMessage(), 400);
        }

    }

    public function sendMobileOtp(Request $request){
        $data = $request->all();
        $is_valid_mobile = 1;
        if(array_key_exists('mobile',$data)){
            $check_mobile = User::where('phone',$data['mobile'])->where('phone_verified_at','!=',null)->first();
            if($check_mobile){
                $is_valid_mobile = 0;
            }
        }
        $data['is_valid_mobile'] = $is_valid_mobile;
        $validator = Validator::make($data, [
            'mobile' => ["required", "min:9", "max:12"],
            'is_valid_mobile' => 'not_in:0',
        ],[
            'is_valid_mobile.not_in' => 'Mobile is already verified.'
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }else{
            //$code = rand(100000,999999);
            $code = '123456';
            $date = date('Y-m-d H:i:s');
            $currentDate = strtotime($date);
            $futureDate = $currentDate+(60*5);
            $user = User::where('phone',$data['mobile'])->first();
            if(!$user){
                $user = new User();
            }
            $user->phone = $data['mobile'];
            $user->otp = $code;
            $user->otp_expire_time = $futureDate;
            $user->save();
            $datauser['otp'] = $code;
            $datauser['user'] = $this->getUserDetail($user->id);
            return $this->successResponse($datauser, 'A six digits Mobile verification code is sent to your Mobile.Please check your Mobile', 200);

        }
    }

    public function reSendMobileOtp(Request $request){
        $data = $request->all();
        $is_valid_mobile = 1;
        if(array_key_exists('mobile',$data)){
            $check_mobile = User::where('phone',$data['mobile'])->where('phone_verified_at','!=',null)->first();
            if($check_mobile){
                $is_valid_mobile = 0;
            }
        }
        $data['is_valid_mobile'] = $is_valid_mobile;
        $validator = Validator::make($data, [
            'mobile' => ["required", "min:9", "max:12"],
            'is_valid_mobile' => 'not_in:0',
        ],[
            'is_valid_mobile.not_in' => 'Mobile is already verified.'
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }else{
            // $code = rand(100000,999999);
            $code = '123456';
            $date = date('Y-m-d H:i:s');
            $currentDate = strtotime($date);
            $futureDate = $currentDate+(60*5);
            $user = User::where('phone',$data['mobile'])->first();
            if(!$user){
                $user = new User();
            }
            $user->phone = $data['mobile'];
            $user->otp = $code;
            $user->otp_expire_time = $futureDate;
            $user->save();

            $datauser['otp'] = $code;
            $datauser['user'] = $this->getUserDetail($user->id);
            return $this->successResponse($datauser, 'A six digits Mobile verification code is sent to your Mobile.Please check your Mobile', 200);

        }
    }

    public function mobileVerifyOtp(Request $request){
        $data = $request->all();
        $validator = Validator::make($data, [
            'mobile' => ["required", "min:9", "max:12"],
            'otp' => "required|max:6",
        ]);
        if($validator->fails()) {
            return $this->errorResponse($validator->getMessageBag()->first(), 422);
        }else{
            $user = User::where('phone',$data['mobile'])->first();
            $input['phone'] = $user->phone;
            $input['password'] = $user->temp_password;
            $date = date('Y-m-d H:i:s');
            $currentTime = strtotime($date);
            if($user->otp == $data['otp']){
                if($currentTime < $user->otp_expire_time){
                    $token = JWTAuth::attempt($input);
                    $user->otp = '';
                    $user->otp_expire_time = '';
                    $user->phone_verified_at = $date;
                    $user->save();

                    $datauser['access_token'] = $token;
                    $datauser['token_type'] = 'bearer';
                    $datauser['preference'] = $user->preference;
                    $datauser['user'] = $this->getUserDetail($user->id);
                    return $this->successResponse($datauser, 'Mobile Number verified successfully.', 200);
                }else{
                    $user->otp = '';
                    $user->otp_expire_time = '';
                    $user->save();
                    return $this->errorResponse('Otp expired..!', 200);
                }
            }else{
                return $this->errorResponse('Please enter valid Otp.', 404);
            }
        }
    }

    public function ContactSearch(Request $request){
        try {

            $data = $request->all();
            $search = $request->search;
            $validator = Validator::make($data, [
                'search' => "required",
            ]);

            if($validator->fails()) {
                return $this->errorResponse($validator->getMessageBag()->first(), 422);
            }
            $distanceUnit = 300;
            $query = User::where('users.status','active');
            $query->where('users.role','user');
            $query->where('phone', $search);
            $Userresults = $query->first();
            $Usercount = $query->count();
            if ($Usercount == "0") {
                return $this->errorResponse('User not found', 403);
            }

        } catch (JWTException $e) {
            return $this->errorResponse($e->getMessage(), 500);

        }
        return $this->successResponse($Userresults, 'User data retrieved.', 200);
    }
}
