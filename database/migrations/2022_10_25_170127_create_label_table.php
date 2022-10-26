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
        Schema::create('label', function (Blueprint $table) {
            $table->id();
            // 身份认证 identity    手机认证 phone    微信认证 wechat    店铺认证 shop
            $table->string('title', 32)->default('')->comment('中文');
            $table->string('key', 32)->default('')->comment('键值');
            $table->tinyInteger('style')->default(1)->comment('展示类型,1默认颜色,2橘色');
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
        Schema::dropIfExists('label');
    }
};
