<?php

Route::group(['namespace' => 'Botble\Payment\Http\Controllers', 'middleware' => 'web'], function () {
    Route::group(['prefix' => 'payments'], function () {
        Route::post('checkout', 'PaymentController@postCheckout')->name('payments.checkout');

        Route::get('status', 'PaymentController@getPayPalStatus')->name('payments.paypal.status');
    });

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'payments/methods'], function () {
            Route::get('', [
                'as'   => 'payments.methods',
                'uses' => 'PaymentController@methods',
            ]);

            Route::post('settings', [
                'as'   => 'payments.settings',
                'uses' => 'PaymentController@updateSettings',
            ]);

            Route::post('', [
                'as'         => 'payments.methods',
                'uses'       => 'PaymentController@updateMethods',
                'middleware' => 'preventDemo',
            ]);

            Route::post('update-status', [
                'as'         => 'payments.methods.update.status',
                'uses'       => 'PaymentController@updateMethodStatus',
                'permission' => 'payment.methods',
            ]);
        });

        Route::group(['prefix' => 'payments/transactions', 'as' => 'payment.'], function () {
            Route::resource('', 'PaymentController')->parameters(['' => 'payment'])->only(['index', 'destroy']);
            Route::get('{chargeId}', 'PaymentController@show')->name('show');
            Route::put('{chargeId}', 'PaymentController@update')->name('update');

            Route::delete('items/destroy', [
                'as'         => 'deletes',
                'uses'       => 'PaymentController@deletes',
                'permission' => 'payment.destroy',
            ]);
        });

    });
});
