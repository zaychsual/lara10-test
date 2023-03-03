<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('log_orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('order_detail_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('qty')->default(0);
            $table->decimal('price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->enum('status', array('process', 'cancel'));
            $table->unsignedBigInteger('customer_id');
            $table->timestamps();

            //relationship order
            $table->foreign('order_id')->references('id')->on('orders');
            //relationship order_detail
            $table->foreign('order_detail_id')->references('id')->on('order_details');
            //relationship product
            $table->foreign('product_id')->references('id')->on('products');
            //relationship product
            $table->foreign('customer_id')->references('id')->on('customers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_orders');
    }
};
