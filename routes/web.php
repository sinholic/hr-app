<?php

use Illuminate\Support\Facades\Route;

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
Auth::routes();
Route::group(["middleware" => "auth", "namespace" => "Admin"], function () {
    Route::get('/', 'DashboardController@index')->name('index');
    Route::group([
        "prefix" => "dashboard"
    ], function () {
        Route::get('hr', 'DashboardController@humanResource')->name('dashboard.hr');
    });
    Route::group([
        "prefix" => "application"
    ], function () {
        // Route::resources([
        //     'recruitments' => 'RecruitmentController'
        // ]);
        // Recruitments
        Route::get('recruitments', 'RecruitmentController@index')->name('recruitments.index');
        Route::get('recruitments/create', 'RecruitmentController@create')->name('recruitments.create');
        Route::post('recruitments', 'RecruitmentController@store')->name('recruitments.store');
        Route::put('recruitments/{model}', 'RecruitmentController@update')->name('recruitments.update');
        Route::get('recruitments/approve/{model}', 'RecruitmentController@approve')->name('recruitments.approve');
        Route::get('recruitments/adjustment/{model}', 'RecruitmentController@adjustment')->name('recruitments.adjustment');
        Route::get('recruitments/reject/{model}', 'RecruitmentController@reject')->name('recruitments.reject');
        // Recruitments - Candidate
        Route::get('recruitments/{model}/candidates', 'CandidateController@index')->name('candidates.view');
        Route::get('recruitments/{model}/candidates/create', 'CandidateController@create')->name('candidates.create');
        Route::post('recruitments/{model}/candidates', 'CandidateController@store')->name('candidates.store');
        Route::put('recruitments/{model_url}/candidates/{model}', 'CandidateController@update')->name('candidates.update');
        Route::get('recruitments/{model_url}/candidates/{model}/edit', 'CandidateController@edit')->name('candidates.edit');
        Route::get('recruitments/{model_url}/candidates/{model}/schedule', 'CandidateController@schedule')->name('candidates.schedule');
        Route::get('recruitments/{model_url}/candidates/{model}/result', 'CandidateController@result')->name('candidates.result');
        Route::get('recruitments/{model_url}/candidates/{model}/add-remark', 'CandidateController@add_remark')->name('candidates.add_remark');
        Route::get('recruitments/{model_url}/candidates/{model}/not-suitable', 'CandidateController@not_suitable')->name('candidates.not_suitable');
        Route::get('recruitments/{model_url}/candidates/{model}/send-offering', 'CandidateController@send_offering')->name('candidates.send_offering');
        Route::get('recruitments/{model_url}/candidates/{model}/approve-join', 'CandidateController@approve_join')->name('candidates.approve_join');
        Route::get('recruitments/{model_url}/candidates/{model}/cancel-join', 'CandidateController@send_offering')->name('candidates.cancel_join');
    });
});
