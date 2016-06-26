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
    'as'   => 'image.index',
    'uses' => 'PageController@index',
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

    Route::get('mass-statistics', [
        'as'   => 'image.mass.statistics',
        'uses' => 'ImageController@massStatistics',
    ]);

    Route::get('mass-stat-result/{fileName?}', [
        'as'   => 'mass.stat.result',
        'uses' => 'ImageController@massStatisticsResult',
    ]);

    Route::get('show/{file?}', [
        'as'   => 'image.show',
        'uses' => 'PageController@show',
    ]);
});
