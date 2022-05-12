<?php

namespace Company\Note\Http\Controllers\Api;

use Illuminate\Support\Facades\Route;

/*
 * --------------------------------------------------------------------------
 *  API Routes
 * --------------------------------------------------------------------------
 *
 *  This file will be loaded by @link \MetaFox\Platform\ModuleManager::getApiRoutes()
 */

Route::group([
    'namespace'  => __NAMESPACE__,
    'middleware' => 'auth:api',
], function () {
    // Note forms
    Route::get('note/form/{id?}', 'NoteController@form');
    Route::get('note/search-form', 'NoteController@searchForm');

    // Put your routes
    Route::resource('note-category', 'NoteController');
    Route::resource('note', 'NoteController');
    Route::patch('note/sponsor/{id}', 'NoteController@sponsor');
    Route::patch('note/feature/{id}', 'NoteController@feature');
    Route::patch('note/approve/{id}', 'NoteController@approve');
    Route::put('note/publish/{id}', 'NoteController@publish');
    Route::patch('note/sponsor-in-feed/{id}', 'NoteController@sponsorInFeed');
});
