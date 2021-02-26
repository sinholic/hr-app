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
        /**
         * 
         * Custom Routes for recruitments and candidates
         * 
         */
        // Recruitments
        Route::get('recruitments', 'RecruitmentController@index')->name('recruitments.index');
        Route::get('recruitments/create', 'RecruitmentController@create')->name('recruitments.create');
        Route::post('recruitments', 'RecruitmentController@store')->name('recruitments.store');
        Route::put('recruitments/{model}', 'RecruitmentController@update')->name('recruitments.update');
        Route::get('recruitments/approve/{model}', 'RecruitmentController@approve')->name('recruitments.approve');
        Route::get('recruitments/adjustment/{model}', 'RecruitmentController@adjustment')->name('recruitments.adjustment');
        Route::get('recruitments/reject/{model}', 'RecruitmentController@reject')->name('recruitments.reject');
        Route::get('recruitments/start/{model}', 'RecruitmentController@start')->name('recruitments.start');
        Route::get('recruitments/end/{model}', 'RecruitmentController@end')->name('recruitments.end');
        Route::get('recruitments/edit/{model}', 'RecruitmentController@edit')->name('recruitments.edit');

        // Recruitments - Candidate
        Route::get('recruitments/{model_url}/candidates', 'CandidateController@index')->name('candidates.index');
        Route::get('recruitments/{model_url}/candidates/create', 'CandidateController@create')->name('candidates.create');
        Route::post('recruitments/{model_url}/candidates', 'CandidateController@store')->name('candidates.store');
        Route::put('recruitments/{model_url}/candidates/{model}', 'CandidateController@update')->name('candidates.update');
        Route::get('recruitments/{model_url}/candidates/edit/{model}', 'CandidateController@edit')->name('candidates.edit');
        Route::get('recruitments/{model_url}/candidates/schedule/{model}', 'CandidateController@schedule')->name('candidates.schedule');
        Route::get('recruitments/{model_url}/candidates/result/{model}', 'CandidateController@result')->name('candidates.result');
        Route::get('recruitments/{model_url}/candidates/suitable/{model}', 'CandidateController@suitable')->name('candidates.suitable');
        Route::get('recruitments/{model_url}/candidates/not-suitable/{model}', 'CandidateController@not_suitable')->name('candidates.not_suitable');
        Route::get('recruitments/{model_url}/candidates/send-offering/{model}', 'CandidateController@send_offering')->name('candidates.send_offering');
        Route::get('recruitments/{model_url}/candidates/approve-join/{model}', 'CandidateController@approve_join')->name('candidates.approve_join');
        Route::get('recruitments/{model_url}/candidates/cancel-join/{model}', 'CandidateController@cancel_join')->name('candidates.cancel_join');
    });

    Route::group([
        "prefix" => "settings"
    ], function () {
        // Users
        Route::get('users', 'UserController@index')->name('users.index');
        Route::get('users/create', 'UserController@create')->name('users.create');
        Route::get('users/edit/{model}',  'UserController@edit')->name('users.edit');
        Route::post('users', 'UserController@store')->name('users.store');
        Route::put('users/{model}', 'UserController@update')->name('users.update');
        Route::get('users/destroy/{model}',  'UserController@destroy')->name('users.destroy');

        // Departments
        Route::get('departments', 'DepartmentController@index')->name('departments.index');
        Route::get('departments/create', 'DepartmentController@create')->name('departments.create');
        Route::get('departments/edit/{model}',  'DepartmentController@edit')->name('departments.edit');
        Route::post('departments', 'DepartmentController@store')->name('departments.store');
        Route::put('departments/{model}', 'DepartmentController@update')->name('departments.update');
    });
});
