<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_verifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->string('nationality');
            $table->string('firstName');
            $table->string('lastName');
            $table->string('middleName');
            $table->string('dateOfBirth');
            $table->string('idType');
            $table->string('backPage')->nullable();
            $table->string('frontPage');
            $table->string('selfieImg');
            $table->boolean('verification_status')->default(0);
            $table->timestamps();
            $table->timestamp("deleted_at")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_verifications');
    }
}
