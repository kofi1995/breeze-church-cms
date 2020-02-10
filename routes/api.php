<?php

use Illuminate\Http\Request;

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
Route::get('people/bulk-upload', 'PeopleController@bulkUpload');

Route::resource('people', 'PeopleController');

Route::get('groups/bulk-upload', 'GroupsController@bulkUpload');

Route::resource('groups', 'GroupsController');
