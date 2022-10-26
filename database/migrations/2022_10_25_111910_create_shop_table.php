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
        Schema::create('shop', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 64)->index();
            $table->string('title');
            $table->text('thumbs')->comment('店铺图,json对象,多个图片地址');
            $table->string('addr', 255)->default('')->comment('详细地址');
            $table->string('lon', 32)->default('')->comment('经度');
            $table->string('lat', 32)->default('')->comment('纬度');
            $table->string('opening_hours', 64)->default('10:00 - 23:00')->comment('营业时间');
            $table->string('desc', 255)->default('')->comment('介绍');
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
        Schema::dropIfExists('shop');
    }
};
