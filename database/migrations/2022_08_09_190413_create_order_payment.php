<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderPayment extends Migration
{
    /**
     * 支付订单表（收款单表） 每次支付或者退款生成的表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 记录发起支付的表
        Schema::create('order_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('order_id', 32)->index()->comment('订单id');
            $table->string('payment_id', 64)->index()->comment('支付单id,由order_id+随机4位组成,用来幂等防止重复支付');  // 2分钟内有该数据并且callback修改状态之前的状态status
            $table->unsignedBigInteger('pay_amount')->default(0)->comment('支付总额:分');
            $table->unsignedBigInteger('user_id')->index()->comment('当前支付用户');
            $table->tinyInteger('status')->default(0)->comment('支付状态,0待支付,1提交支付回调完成,2取消支付(主动取消支付)');
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
        Schema::dropIfExists('order_payment');
    }
}
