<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->string('order_id', 32)->index();
            $table->unsignedBigInteger('user_id')->index()->comment('下单用户');

            // 动态获取项目/技师数据，不再写快照
            $table->unsignedBigInteger('service_id')->index()->comment('项目id');
            $table->unsignedBigInteger('technician_id')->index()->comment('服务技师id');

            $table->unsignedInteger('order_duration')->default(0)->comment('服务时长：小时');
            $table->unsignedBigInteger('order_amount')->default(0)->comment('订单总额:分');  // 当前下单的价格，也就是当前项目*时长的价格
            // 退款成功后，该字段改为3，必须先判断是1状态下才能退款！修改为4已完成之前，必须判断是已支付1状态下
            $table->tinyInteger('status')->default(0)->comment('支付状态,0待支付,1已支付,2已取消,3已退款,4已完成');
            $table->tinyInteger('pay_type')->default(0)->comment('支付类型，1支付宝，2微信');

            // $table->timestamp('server_time')->nullable()->comment('预定的服务时间');
            // $table->string('remark', 255)->default('')->comment('备注');

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
        Schema::dropIfExists('order');
    }
};
