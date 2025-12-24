<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Creates box_order_items table for flexible line items in box orders.
     */
    public function up(): void
    {
        Schema::create('box_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('box_order_id')->constrained('box_orders')->cascadeOnDelete();
            $table->string('product_name');
            $table->integer('quantity');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->timestamps();

            $table->index('box_order_id');
        });

        // Make box_template_id nullable for custom orders
        Schema::table('box_orders', function (Blueprint $table) {
            $table->foreignId('box_template_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('box_order_items');
    }
};
