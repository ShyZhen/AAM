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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uuid', 64)->index();
            $table->string('name');
            $table->string('phone', 64)->default('')->index();
            $table->string('avatar', 255)->default('');
            $table->enum('sex', ['male', 'female', 'secrecy'])->default('secrecy');
            $table->enum('forbidden', ['none', 'yes'])->default('none');  // 用户状态，yes注销，无法登陆
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
