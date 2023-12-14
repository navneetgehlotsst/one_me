<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BussinesCategoryController;
use App\Http\Controllers\Api\BussinesController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\GiftTokenController;
use App\Http\Controllers\Api\GoodWillTokenController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::get('/base-url', [AuthController::class, 'getBaseUrl']);

Route::group(['prefix'=>'auth'], function(){
    //email verfication
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/resend-otp', [AuthController::class, 'reSendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    // mobile verification
    Route::post('/send-mobile-otp', [AuthController::class, 'sendMobileOtp']);
    Route::post('/resend-mobile-otp', [AuthController::class, 'reSendMobileOtp']);
    Route::post('/mobile-verify-otp', [AuthController::class, 'mobileVerifyOtp']);
    // user register login forgot password
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/set-forgot-password', [AuthController::class, 'setForgotPassword']);
    Route::post('/update-profile-image', [AuthController::class, 'updateProfileImage']);
    Route::post('/update-preference', [AuthController::class, 'updatePreference']);
});

Route::middleware('jwt.verify')->group(function() {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::put('/update-profile', [AuthController::class, 'updateProfile']);
    // Bussiness Category
    Route::post('/preference', [BussinesCategoryController::class, 'preference']);
    // Bussuness
    Route::post('/list_businesses', [BussinesController::class, 'listBusinesses']);
    Route::post('/filter_businesses', [BussinesController::class, 'filterBusinesses']);
    Route::post('/search_businesses', [BussinesController::class, 'searchBusinesses']);
    Route::post('/detail_businesses', [BussinesController::class, 'detailBusinesses']);
    // Gift Token
    Route::post('/gift_create', [GiftTokenController::class, 'giftCreate']);
    Route::post('/gift_list', [GiftTokenController::class, 'giftList']);
    Route::post('/gift_detail', [GiftTokenController::class, 'giftDetail']);
    Route::post('/gift_delete', [GiftTokenController::class, 'giftDelete']);
    Route::post('/gift_hide', [GiftTokenController::class, 'giftHide']);
    Route::post('/gift_share', [GiftTokenController::class, 'giftShare']);
    Route::post('/gift_recieved', [GiftTokenController::class, 'giftRecieved']);
    Route::post('/add_token', [GiftTokenController::class, 'addToken']);
    Route::post('/gift_recieved_detail', [GiftTokenController::class, 'giftRecievedDetail']);

    // Good Will Token
    Route::post('/good_will_create', [GoodWillTokenController::class, 'GoodWillCreate']);
    Route::post('/good_will_list', [GoodWillTokenController::class, 'GoodWillList']);

    // Contact Search
    Route::post('/contact_search', [AuthController::class, 'ContactSearch']);
});
