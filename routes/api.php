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


Route::post('addemployee', 'EmployeeController@addEmployee');
Route::post('employee-update', 'EmployeeController@update');
Route::delete('employee-destroy', 'EmployeeController@destroy');
Route::get('show-employee-data', 'EmployeeController@getEmployee'); 

Route::post('employee-attendence', 'AttendenceController@employeeAttendence');
Route::get('show-attendence', 'AttendenceController@getAttendence');

Route::post('addattendencerequst','AttendenceRequestController@addAttendenceRequst');
Route::get('request-check','AttendenceRequestController@requestCheck');
Route::post('request-approvel', 'AttendenceRequestController@requestApprovel');