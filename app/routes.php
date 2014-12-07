<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
App::bind('GameRepositoryInterface', 'EloquentGameRepository');
App::bind('LetterRepositoryInterface', 'EloquentLetterRepository');

Route::group(array('prefix'=>'api'),function(){

    Route::post('games/{id}', array('as'=>'api.games.guess','uses'=>'api\GamesController@guess')); //notice the namespace
    Route::resource('games', 'api\GamesController'); //notice the namespace
});

Route::get('/{path?}', function($path = null)
{
    return View::make('layouts.application')->nest('content', 'app');
})->where('path', '.*'); //regex to match anything (dots, slashes, letters, numbers, etc)
