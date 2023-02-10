<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
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
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('country');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('device_id');
            $table->string('phoneNumber');
            $table->boolean('phone_verify');
            $table->boolean('email_verify');
            $table->string('profile_image');
            $table->string('api_token');
            $table->string('device_id')->nullable();
            $table->enum('user_plan', ['basic', 'premium'])->default('basic');
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
}
