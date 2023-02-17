<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->enum('order_type', ['cr', 'dr'])->default('dr');
            $table->decimal('amount');
            $table->string('receipient_name'); // if it's fundwallet then value == Renta
            $table->string('receipient_source');
            $table->string('receipient_source_name')->nullable();
            $table->string('sender_name'); 
            $table->string('sender_source'); // crypto, card, transffer
            $table->string('sender_source_name')->nullable(); // wallet ID for crypto payment, card. last for digit of card, 
            $table->enum("order_item", ['Property payment', 'withdrawal', 'deposit'])->default("Property payment");
            $table->string("transaction_id");
            $table->string("order_status")->default('pending')->comment("pending, failed, completed");
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
        Schema::dropIfExists('orders');
    }
}
