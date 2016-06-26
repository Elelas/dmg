<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', [
    'as' => 'image.index',
    function () {
        return view('pages.index');
    },
]);

Route::group(['prefix' => 'image'], function () {
    Route::delete('delete/{file?}', [
        'as'   => 'image.delete',
        'uses' => 'ImageController@delete',
    ]);

    Route::post('add', [
        'as'   => 'image.add',
        'uses' => 'ImageController@add',
    ]);

    Route::get('mass-operations', [
        'as'   => 'image.mass.operations',
        'uses' => 'PageController@massOperations',
    ]);

    Route::delete('mass-delete', [
        'as'   => 'image.mass.delete',
        'uses' => 'ImageController@massDelete',
    ]);
});
