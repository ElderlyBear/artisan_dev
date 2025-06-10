<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Info;


Route::prefix('info')->group(function () {
    Route::get('/server',[Info::class,'serverInfo']);
    Route::get('/client', [Info::class,'clientInfo']);
    Route::get('/database', [Info::class,'databaseInfo']);
});

Route::get('/', function () {
    return view('welcome');
});