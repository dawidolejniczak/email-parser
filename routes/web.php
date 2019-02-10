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
    return redirect()->route('mail.index');
});

Auth::routes();

Route::resource('mail', 'MailsController')->only([
    'index', 'store', 'show'
])->middleware('auth');


Route::get('mails/index', 'MailsControlle@index')->name('mails.index');
Route::get('mails/{mail}', 'MailsControlle@show')->name('mails.show');
Route::post('mails', 'MailsController@store')->name('mails.store');
Route::post('mails/webhook', 'MailsController@webhook')->name('mails.webhook');
