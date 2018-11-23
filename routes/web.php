<?php

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::redirect('/', '/home', 301);
Route::get('/home', 'HomeController@index')->name('home');
