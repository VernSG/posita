<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('daily_consignments', function (Blueprint $table) {
            $table->decimal('start_cash', 10, 2)->nullable()->after('partner_id');
            $table->decimal('actual_cash', 10, 2)->nullable()->after('total_revenue');
            $table->timestamp('closed_at')->nullable()->after('updated_at');

            // Make product fields nullable since rows can now represent a session
            $table->string('product_name')->nullable()->change();
            $table->integer('initial_stock')->nullable()->change();
            $table->decimal('base_price', 10, 2)->nullable()->change();
            $table->integer('markup_percentage')->nullable()->change();
            $table->decimal('selling_price', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('daily_consignments', function (Blueprint $table) {
            $table->dropColumn(['start_cash', 'actual_cash', 'closed_at']);

            // Revert strictness (warning: data loss if rows exist without these)
            $table->string('product_name')->nullable(false)->change();
            $table->integer('initial_stock')->nullable(false)->change();
            $table->decimal('base_price', 10, 2)->nullable(false)->change();
            $table->integer('markup_percentage')->nullable(false)->change();
            $table->decimal('selling_price', 10, 2)->nullable(false)->change();
        });
    }
};
