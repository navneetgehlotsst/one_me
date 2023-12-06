<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserAuthController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('/');
Route::get('term-condition', [HomeController::class, 'termCondition'])->name('term-condition');
Route::get('privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('knowledge-base', [HomeController::class, 'knowledgeBase'])->name('knowledge-base');
Route::get('news-blog', [HomeController::class, 'newsBlog'])->name('news-blog');
Route::get('how-it-works', [HomeController::class, 'howitworks'])->name('how-it-works');
Route::get('about-us', [HomeController::class, 'aboutus'])->name('about-us');
Route::get('contact-us', [HomeController::class, 'contactUs'])->name('contact-us.get');
Route::post('contact-us', [HomeController::class, 'contactUsSubmit'])->name('contact-us.post');

Route::get('register', [UserAuthController::class, 'register'])->name('register.get');
Route::post('register-post', [UserAuthController::class, 'registerSubmit'])->name('register.post');
Route::post('register-next', [UserAuthController::class, 'registerNext'])->name('register.next');
Route::get('email/verify', [UserAuthController::class, 'verifyEmail'])->name('email.verify.get');
Route::post('email/verify', [UserAuthController::class, 'verifyEmailSubmit'])->name('email.verify.post');
Route::post('sendotp', [UserAuthController::class, 'sendOtp'])->name('sendotp');
Route::get('login', [UserAuthController::class, 'login'])->name('login.get');
Route::post('login', [UserAuthController::class, 'loginSubmit'])->name('login.post');
Route::get('forgot-password', [UserAuthController::class, 'forgotPassword'])->name('forgot.password.get');
Route::post('forgot-password', [UserAuthController::class, 'forgotPasswordSubmit'])->name('forgot.password.post');
Route::get('forgot-password/verify', [UserAuthController::class, 'verifyForgotPassword'])->name('verify.forgot-password.get');
Route::post('forgot-password/verify', [UserAuthController::class, 'verifyForgotPasswordSubmit'])->name('verify.forgot-password.post');
Route::get('reset-password', [UserAuthController::class, 'resetPassword'])->name('reset.password.get');
Route::post('reset-password', [UserAuthController::class, 'resetPasswordSubmit'])->name('reset.password.post');




Route::name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [AuthController::class, 'admin']);
    Route::get('login', [AuthController::class, 'index'])->name('login');
    Route::post('login', [AuthController::class, 'postLogin'])->name('login.post');
    // Route::get('registration', [AuthController::class, 'registration'])->name('register');
    // Route::post('post-registration', [AuthController::class, 'postRegistration'])->name('register.post');
    Route::get('forget-password', [AuthController::class, 'showForgetPasswordForm'])->name('forget.password.get');
    Route::post('forget-password', [AuthController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');
});
Route::middleware(['auth'])->group(function () {
    Route::middleware(['admin'])->group(function () {
        Route::name('admin.')->prefix('admin')->group(function () {
            Route::get('dashboard', [AuthController::class, 'adminDashboard'])->name('dashboard');
            Route::get('change-password', [AuthController::class, 'changePassword'])->name('change.password');
            Route::post('update-password', [AuthController::class, 'updatePassword'])->name('update.password');
            Route::get('logout', [AuthController::class, 'logout'])->name('logout');
            Route::get('profile', [AuthController::class, 'userProfile'])->name('profile');
            Route::post('profile', [AuthController::class, 'updateUserProfile'])->name('update.profile');

            Route::name('users.')->group(function () {
                Route::get("users",  [UserController::class, 'index'])->name('index');
                Route::get("users/alluser",  [UserController::class, 'getallUser'])->name('alluser');
                Route::post("users/status",  [UserController::class, 'userStatus'])->name('status');
                Route::delete("users/delete/{id}",  [UserController::class, 'destroy'])->name('destroy');
                Route::get("users/{id}",  [UserController::class, 'show'])->name('show');
            });

        });
    });

    Route::middleware(['user'])->group(function () {
        Route::get('my-account', [UserAuthController::class, 'myAccount'])->name('my-account');
        Route::post('my-account', [UserAuthController::class, 'updateMyAccount'])->name('my-account.update');
        Route::get('change-password', [UserAuthController::class, 'changePassword'])->name('change.password');
        Route::post('update-password', [UserAuthController::class, 'updatePassword'])->name('update.password');
        Route::get('logout', [UserAuthController::class, 'logout'])->name('logout');


    });
});
