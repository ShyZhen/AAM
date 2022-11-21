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
        Schema::create('technician', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 64)->index();
            $table->string('name', 64);
            $table->string('avatar', 255)->default('');
            $table->string('photo_wall', 255)->default('')->comment('图片墙背景');
            $table->string('phone', 64)->default('');
            $table->string('wechat', 64)->default('');
            $table->unsignedBigInteger('shop_id')->index();

            $table->enum('sex', ['male', 'female', 'secrecy'])->default('secrecy');
            $table->date('birthday')->default('1970-01-01');

            // $table->unsignedDecimal('score', 8, 1)->comment('评分');
            $table->string('score', 8)->comment('评分');
            $table->unsignedInteger('order_count')->default(0)->comment('订单数');
            $table->unsignedInteger('fans_count')->default(0)->comment('关注数/粉丝数');

            $table->string('addr', 255)->default('')->comment('详细地址');
            $table->string('lon', 32)->default('')->comment('经度');
            $table->string('lat', 32)->default('')->comment('纬度');

            $table->string('intro', 255)->default('')->comment('服务者介绍');
            $table->tinyInteger('is_pretty')->default(0)->comment('是否是颜值出众的(颜值区),0否,1是');
            $table->tinyInteger('is_recommend')->default(0)->comment('是否是推荐到首页,0否,1是');

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
        Schema::dropIfExists('technician');
    }
};
