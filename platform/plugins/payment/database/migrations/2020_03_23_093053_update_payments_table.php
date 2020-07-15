<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->unsigned()->change();
            $table->integer('order_id')->unsigned()->nullable();
            $table->string('status', 60)->default('pending')->nullable();
            $table->string('payment_type')->default('confirm')->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->decimal('refunded_amount', 15, 2)->unsigned()->nullable();
            $table->string('refund_note', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->unsigned()->change();
            $table->dropColumn([
                'status',
                'order_id',
                'payment_type',
                'customer_id',
                'refunded_amount',
                'refund_note',
            ]);
        });
    }
}
