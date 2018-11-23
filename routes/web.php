<?php

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::redirect('/', '/groups', 301);
Route::get('/logout', 'Auth\LoginController@logoutPage')->name('logout');

Route::get('/audio/{id}', 'AudioController@details')->name('audio');
Route::get('/raw/{id}', 'AudioController@raw')->name('raw_audio');
Route::get('/download/{id}', 'AudioController@download')->name('raw_download');

Route::resource('groups', 'GroupController')->only(['index', 'create', 'show', 'store']);
Route::resource('buckets', 'BucketController')->only(['index', 'create', 'show', 'store']);
Route::resource('users', 'UserController')->only(['index', 'create', 'show', 'store']);