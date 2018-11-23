<?php

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::redirect('/', '/home', 301);
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/logout', 'Auth\LoginController@logoutPage')->name('logout');

Route::get('/groups', 'GroupController@index')->name('groups');
Route::get('/buckets', 'BucketController@index')->name('buckets');
Route::get('/users', 'UserController@index')->name('users');

Route::get('/group/{id}', 'GroupController@details')->name('group');
Route::get('/bucket/{id}', 'BucketController@details')->name('bucket');
Route::get('/audio/{id}', 'AudioController@details')->name('audio');
Route::get('/raw/{id}', 'AudioController@raw')->name('raw_audio');
Route::get('/download/{id}', 'AudioController@download')->name('raw_download');