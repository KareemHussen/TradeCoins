<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoomController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
    Route::get('me', 'me');
    Route::post('sendEmailOtp', 'sendEmailOtp');
    Route::post('checkEmailOtp', 'checkEmailOtp');
    Route::post('sendPhoneOtp', 'sendPhoneOtp');
    Route::post('checkPhoneOtp', 'checkPhoneOtp');
    Route::post('resetPassword', 'resetPassword');
    Route::post('forgetPassword', 'forgetPassword');
    Route::post('checkForgetPasswordOtp', 'checkForgetPasswordOtp');


});

Route::controller(RoomController::class)->group(function () {
    Route::post('sendMessage', 'sendMessage');
    Route::get('roomsOfUser', 'getRoomsOfUser');
    Route::get('messagesOfRoom', 'getMessagesOfRoom');

});



Route::controller(AdController::class)->group(function () {
    Route::get('getBuyAds', 'getBuyAds');
    Route::get('getSellAds', 'getSellAds');
    Route::get('getMyAds', 'getMyAds');
    Route::post('createAd', 'createAd');
    Route::post('updateAd', 'updateAd');
    Route::post('deleteAd', 'deleteAd');
    Route::get('getFeedBacksOfUser', 'getFeedBacksOfUser');
    Route::post('createFeedBack', 'createFeedBack');

});

Route::get('gamed' , function (Request $request){



    return "gaaaaamed";
});

// pkrdfzammwqwsfhc
