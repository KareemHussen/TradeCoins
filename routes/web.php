<?php

use App\Http\Controllers\MailController;
use App\Mail\RegisterMail;
use App\Models\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Predis\Command\Redis\SUBSCRIBE;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('mail', [MailController::class , 'sendMail']);

Route::get('ahh', function (){

    return "Gaaaaaamed";
})->middleware(['verifiedNumber']);


Route::get('/messageUrl', function () {
    $message = Message::create([
        'message'=>"Gamed"
    ]);

    return $message;
})->name("gamed");
