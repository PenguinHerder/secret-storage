<?php

Auth::routes(['register' => false, 'reset' => false, 'verify' => false]);
Route::get('/invitation/{token}', 'MemberController@join')->name('join');
Route::post('/invitation/complete', 'MemberController@complete')->name('complete');

Route::redirect('/', '/buckets', 301);

Route::get('/raw/{audio}', 'AudioController@raw')->name('raw_audio');
Route::get('/download/{audio}', 'AudioController@download')->name('raw_download');
Route::post('/analysis-save/{audio}', 'AudioController@saveAnalysis')->name('save_analysis');
Route::post('/analysis-approve/{audio}', 'AudioController@approveAnalysis')->name('approve_analysis');

Route::resource('groups', 'GroupController')->only(['index', 'create', 'show', 'store']);
Route::resource('buckets', 'BucketController')->only(['index', 'create', 'show', 'store']);
Route::resource('members', 'MemberController')->only(['index', 'create', 'show', 'store']);
Route::resource('audios', 'AudioController')->only(['index', 'create', 'show', 'store']);
