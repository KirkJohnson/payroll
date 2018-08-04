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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/','ReportController@index')->name('reports')->middleware('auth');
Route::post('/upload', 'ReportController@upload')->name('upload')->middleware('auth');
Route::get('/view_report/{id}', 'ReportController@view')->name('view_report')->middleware('auth');
Route::get('/home', 'HomeController@index')->name('home');
