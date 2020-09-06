<?php

Route::group(['namespace' => 'Botble\Referral\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('core.base.general.admin_dir'), 'middleware' => 'auth'], function () {

        Route::resource('referral', 'ReferralController', ['names' => 'referral']);
        Route::group(['prefix' => 'commission'], function () {
            Route::resource('commission', 'CommissionController', ['names' => 'commission']);
            Route::post('commission/returns',[
                'as'=>'commission.calculate',
                'uses'=>'CommissionController@calCommission',
                'permission'=>'commission.calculate',
            ]);
            Route::get('commission/setpaid',[
                'as'=>'commission.setpaid',
                'uses'=>'CommissionController@setPaid',
                'permission'=>'commission.setpaid',
            ]);
        });
        Route::group(['prefix' => 'referral'], function () {

            Route::delete('items/destroy', [
                'as'         => 'career.deletes',
                'uses'       => 'CareerController@deletes',
                'permission' => 'career.destroy',
            ]);
        });
    });

});
