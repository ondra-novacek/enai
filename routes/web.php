<?php

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

#starting page
Route::get('/', 'PagesController@index');

#results of a survey
Route::post('/survey', 'SurveyController@store')->name('store');
Route::get('/survey', 'SurveyController@show')->name('show');

#export excel data
Route::get('/edit/export_excel/excel', 'ExportDataController@export')->name('export_excel.excel');
Route::get('/edit/export_excel/csv', 'ExportDataController@exportCsv')->name('export_excel.excel');

#authentication creation
Auth::routes();
Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout')->name('logout');

#home -> requires user to be logged in (in construct there is middleware)
Route::get('/edit', 'AdminController@index')->name('edit');

#view for page if user entered page with unsupported broswer/browser version
Route::get('/support', function () {
    return view('pages/notSupported');
})->name('notsupported');

//Route::resource('/admin', 'AdminController');

#survey starting tests
Route::get('/{student}', 'PagesController@survey')->name('student');
Route::get('/{teacher}', 'PagesController@survey')->name('teacher');
Route::get('/{management}', 'PagesController@survey')->name('management');
Route::get('/{researcher}', 'PagesController@survey')->name('researcher');