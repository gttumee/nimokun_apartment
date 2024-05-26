<?php

use App\Http\Controllers\DataApiController;
use App\Http\Controllers\QrCodeController;
use App\Models\CustomerContact;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use Filament\Notifications\Notification;
use Carbon\Carbon;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/qrcode',[QrCodeController::class,'generateQRCode'])->name('qrcode');
Route::get('/get',[DataApiController::class,'dataNotifications']);