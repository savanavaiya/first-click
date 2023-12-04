<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DataController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group( function () {

    Route::post('/import/data', [DataController::class,'importdata']);

    //
    Route::post('/import/data/google/spreadsheet', [DataController::class,'importdataspreadsheet']);


    Route::post('/add/data/manually', [DataController::class,'adddatamanually']);


    Route::post('/get/data', [DataController::class,'getdata']);

    Route::post('/update/data', [DataController::class,'updatedata']);

    Route::delete('/delete/data', [DataController::class,'deletedata']);

    Route::post('/user/update/profile', [AuthController::class,'updateprofile']);

    Route::get('/user/logout', [AuthController::class,'logout']);

    Route::delete('/user/delete', [AuthController::class,'userdelete']);

    //for brand logo upload
    Route::post('/add/brand/logo', [DataController::class,'addbrandlogo']);

    Route::get('/get/brand/logo', [DataController::class,'getbrandlogo']);

    //for store image upload
    Route::post('/add/store/image',[DataController::class,'addstoreimage']);

    Route::get('/get/store/image', [DataController::class,'getstoreimage']);

    Route::post('/update/store/image', [DataController::class,'updatestoreimage']);

});

Route::post('/user/create', [AuthController::class,'signup']);

Route::post('/user/login', [AuthController::class,'login']);

Route::post('/user/location', [DataController::class,'userlocation']);
