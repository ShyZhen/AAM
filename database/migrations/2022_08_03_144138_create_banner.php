<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBanner extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 128)->default('');
            $table->string('image', 255)->default('')->comment('单张图片url,255字符以内');
            $table->string('uri', 255)->default('')->comment('跳转携带的uri,255字符以内');
            $table->integer('sort')->default(0)->comment('支持修改排序字段，默认asc sort,asc id');
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
        Schema::dropIfExists('banner');
    }
}
