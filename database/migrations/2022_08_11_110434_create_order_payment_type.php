<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPaymentType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 无论是支付，还是退款，都记录的总结表
        Schema::create('order_payment_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('payment_id', 64)->index()->comment('支付单ID');
            $table->tinyInteger('action_type')->default(1)->comment('类型,1支付,2退款');
            $table->unsignedBigInteger('pay_amount')->default(0)->comment('冗余-支付总额:分');
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
        Schema::dropIfExists('order_payment_type');
    }
}
