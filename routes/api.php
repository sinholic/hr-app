<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["namespace" => "API"], function () {
    Route::prefix('recruitments')->group(function () {
        Route::get('open', 'RecruitmentController@open');
        Route::get('total_header', 'RecruitmentController@total_header');
    });
    Route::prefix('candidates')->group(function () {
        Route::get('processed', 'CandidateController@processed');
    });

    Route::prefix('attendances')->group(function () {
        Route::post('/', 'AttendanceController@attendance');
    });

    Route::prefix('employee')->group(function () {
        Route::post('register', 'EmployeeController@registerEmployee');
    });
});
