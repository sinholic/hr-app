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
        // After first input CV, user decide if the CV is suitable or not
        Route::get('recruitments/{model_url}/candidates/cv-suitable/{model}', 'CandidateController@cv_suitable')->name('candidates.cv_suitable');
        Route::get('recruitments/{model_url}/candidates/cv-not-suitable/{model}', 'CandidateController@cv_not_suitable')->name('candidates.cv_not_suitable');
        // After CV Suitable, then send the screening form 
        Route::get('recruitments/{model_url}/candidates/send-screening-form/{model}', 'CandidateController@send_screening_form')->name('candidates.send_screening_form');
        // After candidate send back the screening form
        Route::get('recruitments/{model_url}/candidates/receive-screening-form/{model}', 'CandidateController@receive_screening_form')->name('candidates.receive_screening_form');
        Route::get('recruitments/{model_url}/candidates/suitable-to-interview/{model}', 'CandidateController@suitable_to_interview')->name('candidates.suitable_to_interview');
        Route::get('recruitments/{model_url}/candidates/not-suitable-to-interview/{model}', 'CandidateController@not_suitable_to_interview')->name('candidates.not_suitable_to_interview');
        // Schedule interview for the candidate
        Route::get('recruitments/{model_url}/candidates/schedule/{model}', 'CandidateController@schedule')->name('candidates.schedule');
        // Add result for the candidate
        Route::get('recruitments/{model_url}/candidates/result/{model}', 'CandidateController@result')->name('candidates.result');
        // Suitable or not for OL
        Route::get('recruitments/{model_url}/candidates/not-suitable-for-ol/{model}', 'CandidateController@not_suitable_for_ol')->name('candidates.not_suitable_for_ol');
        Route::get('recruitments/{model_url}/candidates/suitable-for-ol/{model}', 'CandidateController@suitable_for_ol')->name('candidates.suitable_for_ol');
        // Send OL
        Route::get('recruitments/{model_url}/candidates/send-offering/{model}', 'CandidateController@send_offering')->name('candidates.send_offering');
        // On Board
        Route::get('recruitments/{model_url}/candidates/approve-join/{model}', 'CandidateController@approve_join')->name('candidates.approve_join');
        // Cancel join
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

        // Candidate Status
        Route::get('candidate-statuses', 'CandidateStatusController@index')->name('candidate_status.index');
        Route::get('candidate-statuses/create', 'CandidateStatusController@create')->name('candidate_status.create');
        Route::get('candidate-statuses/edit/{model}',  'CandidateStatusController@edit')->name('candidate_status.edit');
        Route::post('candidate-statuses', 'CandidateStatusController@store')->name('candidate_status.store');
        Route::put('candidate-statuses/{model}', 'CandidateStatusController@update')->name('candidate_status.update');
    });
});
