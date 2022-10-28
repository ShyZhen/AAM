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
        Schema::create('service', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 64)->index();
            $table->unsignedBigInteger('technician_id')->index();
            $table->string('title', 128)->default('');
            $table->text('thumbs')->comment('项目图,json对象,多个图片地址');
            $table->string('thumb_min', 255)->default('')->comment('项目缩略图，单张');
            $table->unsignedBigInteger('tour_price')->default(0)->comment('每小时价格:分');
            $table->text('desc')->comment('项目介绍');
            $table->unsignedInteger('sold_count')->default(0)->comment('已售数量');
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
        Schema::dropIfExists('service');
    }
};
