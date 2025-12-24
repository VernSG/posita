<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * Changes default_markup_percent to default_selling_price for manual pricing.
     */
    public function up(): void
    {
        Schema::table('product_templates', function (Blueprint $table) {
            // Add new selling price column
            $table->decimal('default_selling_price', 12, 2)->nullable()->after('base_price');
        });

        // Migrate existing data: calculate selling price from markup
        DB::statement('UPDATE product_templates SET default_selling_price = base_price * (1 + default_markup_percent / 100)');

        Schema::table('product_templates', function (Blueprint $table) {
            // Remove old markup column
            $table->dropColumn('default_markup_percent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_templates', function (Blueprint $table) {
            $table->integer('default_markup_percent')->default(10)->after('base_price');
        });

        Schema::table('product_templates', function (Blueprint $table) {
            $table->dropColumn('default_selling_price');
        });
    }
};
