<?php

Route::group(['namespace' => 'Botble\Location\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => 'countries', 'as' => 'country.'], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'CountryController@getList',
            'permission' => 'country.index',
        ]);
    });

    Route::group(['prefix' => 'states', 'as' => 'state.'], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'StateController@getList',
            'permission' => 'state.index',
        ]);

        Route::get('get-states', [
            'as'   => 'get-states',
            'uses' => 'StateController@getArrayStates',
        ]);
    });

    Route::group(['prefix' => 'cities', 'as' => 'city.'], function () {
        Route::get('list', [
            'as'         => 'list',
            'uses'       => 'CityController@getList',
            'permission' => 'city.index',
        ]);

        Route::get('get-cities-by-state', [
            'as'   => 'get-cities-by-state',
            'uses' => 'CityController@getCitiesByStateId',
        ]);
    });

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'countries', 'as' => 'country.'], function () {
            Route::resource('', 'CountryController')->parameters(['' => 'country']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CountryController@deletes',
                'permission' => 'country.destroy',
            ]);
        });

        Route::group(['prefix' => 'states', 'as' => 'state.'], function () {
            Route::resource('', 'StateController')->parameters(['' => 'state']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'StateController@deletes',
                'permission' => 'state.destroy',
            ]);
        });

        Route::group(['prefix' => 'cities', 'as' => 'city.'], function () {
            Route::resource('', 'CityController')->parameters(['' => 'city']);
            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'CityController@deletes',
                'permission' => 'city.destroy',
            ]);
        });
    });

});
