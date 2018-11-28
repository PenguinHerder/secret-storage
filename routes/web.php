<?php

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);

Route::redirect('/', '/buckets', 301);
Route::get('/logout', 'Auth\LoginController@logoutPage')->name('logout');

Route::get('/raw/{audio}', 'AudioController@raw')->name('raw_audio');
Route::get('/download/{audio}', 'AudioController@download')->name('raw_download');
Route::post('/analysis-save/{audio}', 'AudioController@saveAnalysis')->name('save_analysis');
Route::post('/analysis-approve/{audio}', 'AudioController@approveAnalysis')->name('approve_analysis');

Route::resource('groups', 'GroupController')->only(['index', 'create', 'show', 'store']);
Route::resource('buckets', 'BucketController')->only(['index', 'create', 'show', 'store']);
Route::resource('members', 'MemberController')->only(['index', 'create', 'show', 'store']);
Route::resource('audios', 'AudioController')->only(['index', 'create', 'show', 'store']);

Route::get('/privacy', 'ExtraController@privacy')->name('privacy');