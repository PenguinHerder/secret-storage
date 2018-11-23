<?php

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::redirect('/', '/home', 301);
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/audio/{id}', 'AudioController@details')->name('audio');
Route::get('/raw/{id}', 'AudioController@raw')->name('raw_audio');
Route::get('/download/{id}', 'AudioController@download')->name('raw_download');