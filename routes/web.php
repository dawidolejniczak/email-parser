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

Route::get('/', function () {
    return redirect()->route('mails.index');
});

Auth::routes();


Route::middleware('auth')->prefix('mails')->group(function () {
    Route::get('/', 'MailsController@index')->middleware('auth')->name('mails.index');
    Route::get('mails/{mail}', 'MailsController@show')->name('mails.show');
    Route::post('/', 'MailsController@store')->name('mails.store');
});

Route::post('mails/webhook', 'MailsController@webhook')->name('mails.webhook');
