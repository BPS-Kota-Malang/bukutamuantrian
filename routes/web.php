<?php

use App\Http\Controllers\WppConnectController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Route::post('/wa-server/create-session', [WppConnectController::class, 'createSession']);
Route::get('/wa-server/send-message', [WppConnectController::class, 'sendMessage']);
// Route::get('/public-queue', PublicQueueForm::class)->name('public.queue.form');
// Route::middleware('web')->get('/public-queue', PublicQueueForm::class)->name('public.queue.form');
// Route::middleware('web')->get('/guest/public-queue', PublicQueueForm::class)->name('public.queue.form');
