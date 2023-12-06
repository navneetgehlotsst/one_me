<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Category;
use App\Models\EmailOtp;
use App\Mail\UserSendOtpMail;
use App\Mail\UserRegisterVerifyMail;
use Mail,Hash,File,Auth,DB,Helper,Exception,Session;

class UserAuthController extends Controller
{
    public function register(){
        return view('web.auth.register');
    }

    public function registerSubmit(Request $request){
        $request->validate([
            'business_name' => 'required|string',
            'full_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|digits_between:9,12|unique:users',
            'password' => 'required|min:6',
            'repeat_password' => 'required|min:6|same:password',
        ]);
        try{
            $categories = Category::all();

            //======================== Add User  ===============//
            $bissunesdata = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'business_name' => $request->business_name,
                'role' => 'business',
            ];
            $bissunesInsert = User::create($bissunesdata);
            $bissunesID = $bissunesInsert->id;
            return view('web.auth.register_next',compact('bissunesID','categories'));
        }catch(Exception $e){
            return back()->withInput()->withError($e->getMessage());
        }
    }

    public function registerNext(Request $request){
        $request->validate([
            'business_name' => 'required|string',
            'full_name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|numeric|digits_between:9,12|unique:users',
            'password' => 'required|min:6',
            'repeat_password' => 'required|min:6|same:password',
        ]);
        try{
            $categories = Category::all();

            //======================== Add User  ===============//
            $bissunesdata = [
                'full_name' => $request->full_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => bcrypt($request->password),
                'business_name' => $request->business_name,
                'role' => 'business',
            ];
            $bissunesInsert = User::create($bissunesdata);
            $bissunesID = $bissunesInsert->id;
            return view('web.auth.register_next',compact('bissunesID','categories'));
        }catch(Exception $e){
            return back()->withInput()->withError($e->getMessage());
        }
    }

    public function verifyEmail(){
        return view('web.auth.email_verify');
    }

    public function verifyEmailSubmit(Request $request){
        try{
            $user = Session::get('register_user');
            $verify_user = EmailOtp::where('email',$user->email)->orderBy('id','desc')->first();
            $date = date('Y-m-d H:i:s');
            $currentTime = strtotime($date);
            if($verify_user){
                if($verify_user->otp == $request->code){
                    if($verify_user->otp_expire_time > $currentTime){
                        $user->email_verified_at = $date;
                        $user->save();
                        $udpate_user = User::find($user->id);
                        $udpate_user->member_id = 'de'.$user->id;
                        $udpate_user->save();
                        Session::forget('register_user');
                        Mail::to($user->email)->send(new UserRegisterVerifyMail($user));
                        return redirect()->route('login.get')->withSuccess('Account created successfully!Please Login.');
                    }else{
                        return back()->withInput()->withError('Verification code expired!');
                    }
                }else{
                    return back()->withInput()->withError('invalid verification code!');
                }
            }else{
                return back()->withInput()->withError('User not found!');
            }

        }catch(Exception $e){
            return back()->withInput()->withError($e->getMessage());
        }
    }

    public function sendOtp(){
        $user = Session::get('register_user');
        if(!$user){
            $user = Session::get('forgot_user');
        }
        $code = rand(1000,9999);
        $date = date('Y-m-d H:i:s');
        $currentDate = strtotime($date);
        $futureDate = $currentDate+(60*5);

        EmailOtp::where('email',$user->email)->forceDelete();
        $email_otp = new EmailOtp();
        $email_otp->email = $user->email;
        $email_otp->otp = $code;
        $email_otp->otp_expire_time = $futureDate;
        $email_otp->save();
        Mail::to($user->email)->send(new UserSendOtpMail($user, $code));
    }

    public function login(){
        return view('web.auth.login');
    }

    public function loginSubmit(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        try{
            $credentials = $request->only('email', 'password');
            if(Auth::attempt($credentials)) {
                return redirect()->route('/')->withSuccess('Loggedin Successful');
            }
            return back()->withInput()->withError('You have entered invalid credentials');
        }catch(Exception $e){
            return back()->withInput()->withError($e->getMessage());
        }

    }

    public function forgotPassword(){
        return view('web.auth.forgot_password');
    }

    public function forgotPasswordSubmit(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
        try{
            $user = User::where('email',$request->email)->first();
            $code = rand(1000,9999);
            $date = date('Y-m-d H:i:s');
            $currentDate = strtotime($date);
            $futureDate = $currentDate+(60*5);

            EmailOtp::where('email',$user->email)->forceDelete();
            $email_otp = new EmailOtp();
            $email_otp->email = $user->email;
            $email_otp->otp = $code;
            $email_otp->otp_expire_time = $futureDate;
            $email_otp->save();
            Mail::to($user->email)->send(new UserSendOtpMail($user, $code));
            Session::forget('forgot_user');
            Session::put('forgot_user', $user);
            return redirect()->route('verify.forgot-password.get')->withSuccess('Verification code sent successfully on your email address');

        }catch(Exception $e){
            return back()->withInput()->withError($e->getMessage());
        }

    }

    public function verifyForgotPassword(){
        return view('web.auth.forgot_password_verify');
    }

    public function verifyForgotPasswordSubmit(Request $request){
        try{
            $user = Session::get('forgot_user');
            $verify_user = EmailOtp::where('email',$user->email)->orderBy('id','desc')->first();
            $date = date('Y-m-d H:i:s');
            $currentTime = strtotime($date);
            if($verify_user){
                if($verify_user->otp == $request->code){
                    if($verify_user->otp_expire_time > $currentTime){
                        return redirect()->route('reset.password.get')->withSuccess('Verified successfully!Please reset password');
                    }else{
                        return back()->withInput()->withError('Verification code expired!');
                    }
                }else{
                    return back()->withInput()->withError('Invalid verification code!');
                }
            }else{
                return back()->withInput()->withError('User not found!');
            }

        }catch(Exception $e){
            return back()->withInput()->withError($e->getMessage());
        }
    }

    public function resetPassword(){
        return view('web.auth.reset_password');
    }

    public function resetPasswordSubmit(Request $request){
        $request->validate([
            'password' => 'required|confirmed|min:6',
        ]);
        try{
            $user = Session::get('forgot_user');
            if($user){
                if(Hash::check($request->password,$user->password)){
                    return back()->withInput()->withError('Cannot use your old password as new password');
                }else{
                    $user->password = Hash::make($request->password);
                    $user->save();
                    Session::forget('forgot_user');
                    return redirect()->route('login.get')->withSuccess('Password reset successfully!Please Login');
                }
            }
            else{
                return back()->withInput()->withError('User not exist');
            }
        }
        catch(Exception $e){
            return back()->withInput()->withError($e->getMessage());
        }
    }

    public function myAccount(){
        $user = Auth::user();
        return view('web.auth.my_account',compact('user'));
    }

    public function updateMyAccount(Request $request){
        $user = Auth::user();
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'phone' => 'required|numeric|digits_between:9,12|unique:users,phone,'.$user->id,
            'company_name' => 'required|string',
            'address' => 'required',
            'avatar'  =>  'sometimes|mimes:jpeg,jpg,png|max:8000',
        ]);

        try{

            //======================== Add User  ===============//
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->full_name = $request->first_name.' '.$request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->company_name = $request->company_name;
            $user->address = $request->address;
            $user->city = $request->city ?? '';
            $user->state = $request->state ?? '';
            $user->country = $request->country ?? '';
            $user->country_code = $request->country_code ?? '';
            $user->zipcode = $request->zipcode ?? '';
            $user->latitude = $request->latitude ?? '';
            $user->longitude = $request->longitude ?? '';

            $input = $request->all();
            if(array_key_exists('avatar',$input)){
                $file = $request->file('avatar');
                if($file){
                    $filename   = time().$file->getClientOriginalName();
                    $folder = 'uploads/user/';
                    $path = public_path($folder);
                    if(!File::exists($path)) {
                        File::makeDirectory($path, $mode = 0777, true, true);
                    }
                    $file->move($path, $filename);
                    $user->avatar   = $folder.$filename;
                }
            }
            $user->save();
            return back()->withSuccess('Profile updated successfully');
        }catch(Exception $e){
            return back()->withInput()->withError($e->getMessage());
        }
    }


    public function changePassword()
    {
        return view("web.auth.change_password");
    }

    public function updatePassword(Request $request)
    {
        $data = $request->all();
        $request->validate([
            "old_password" => "required",
            "password" => "required|confirmed",
        ]);

        try{
            if(!Hash::check($request->old_password, auth()->user()->password)) {
                return back()->with("error", "Old Password Doesn't match!");
            }
            User::whereId(auth()->user()->id)->update([
                "password" => Hash::make($request->password),
            ]);
            return back()->with("success", "Password changed successfully!");

        }catch(Exception $e){
            return back()->withInput()->withError($e->getMessage());
        }
    }

    public function logout(){
        Auth::logout();
        return redirect('/')->withSuccess('Logout Successful');
    }


}
