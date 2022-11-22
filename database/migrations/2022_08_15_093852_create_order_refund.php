<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderRefund extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 记录申请退款的表
        Schema::create('order_refund', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_id', 32)->index()->comment('订单id');
            $table->string('payment_id', 64)->index()->comment('支付成功的那次payment_id');
            $table->unsignedBigInteger('pay_amount')->default(0)->comment('支付总额:分');
            $table->unsignedBigInteger('user_id')->index()->comment('当前支付用户');
            $table->tinyInteger('status')->default(0)->comment('支付状态,0发起退款,1已处理完成(需要同步order表状态)');
            $table->tinyInteger('pay_type')->default(0)->comment('支付类型，1支付宝，2微信');
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
        Schema::dropIfExists('order_refund');
    }
}
