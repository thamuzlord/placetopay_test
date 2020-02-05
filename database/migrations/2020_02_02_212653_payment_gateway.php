<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PaymentGateway extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateway', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_oder_number',45);
            $table->string('payment_request_id',45);
            $table->string('payment_processurl',200);
            $table->string('payment_status',45);
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateway');
    }
}
