<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateColumnPaymentChannel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('payments', 'payment_chanel')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->renameColumn('payment_chanel', 'payment_channel');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->renameColumn('payment_channel', 'payment_chanel');
        });
    }
}
