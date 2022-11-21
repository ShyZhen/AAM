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
        // 用户粉丝系统表
        Schema::create('users_follow', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('master_user_id')->index();             // 被关注者 技师
            $table->unsignedBigInteger('following_user_id')->index();          // 关注动作的发出者，成为master的粉丝
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
        Schema::dropIfExists('users_follow');
    }
};
