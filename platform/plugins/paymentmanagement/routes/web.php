<?php

Route::group(['namespace' => 'Botble\Paymentmanagement\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'paymentmanagement', 'as' => 'paymentmanagement.'], function () {
            Route::resource('', 'PMController')->parameters(['' => 'paymentmanagement']);

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'PostController@deletes',
                'permission' => 'posts.destroy',
            ]);

            Route::get('widgets/recent-posts', [
                'as'         => 'widget.recent-posts',
                'uses'       => 'PostController@getWidgetRecentPosts',
                'permission' => 'posts.index',
            ]);
        });


    });

    if (defined('THEME_MODULE_SCREEN_NAME')) {
        Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
            Route::get('search', [
                'as'   => 'public.search',
                'uses' => 'PublicController@getSearch',
            ]);

            Route::get('tag/{slug}', [
                'as'   => 'public.tag',
                'uses' => 'PublicController@getTag',
            ]);
        });
    }
});
