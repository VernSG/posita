<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Database Optimization Migration
     * 
     * This migration adds performance-critical indexes for:
     * - Date-based filtering (statistics, trends, reports)
     * - Status filtering (dashboard queries)
     * - Composite indexes for common query patterns
     * 
     * @see https://laravel.com/docs/11.x/migrations#creating-indexes
     */
    public function up(): void
    {
        // ========================================
        // box_orders - Optimize for trend & status queries
        // ========================================
        Schema::table('box_orders', function (Blueprint $table) {
            // Index for date-based trend queries
            // Used by: getSalesTrend(), getDailySummary()
            $table->index('created_at', 'box_orders_created_at_index');

            // Composite index for common status + date queries
            // Used by: Dashboard stats, trend calculations
            $table->index(['status', 'created_at'], 'box_orders_status_created_at_index');

            // Composite index for upcoming pickups with status filter
            // Used by: getUpcomingOrders(), countdown notifications
            $table->index(['status', 'pickup_datetime'], 'box_orders_status_pickup_index');
        });

        // ========================================
        // shop_sessions - Optimize for reporting queries
        // ========================================
        Schema::table('shop_sessions', function (Blueprint $table) {
            // Composite index for closed sessions by date (most common query)
            // Used by: getSalesTrend(), getDailySummary(), getSessionHistory()
            $table->index(['status', 'opened_at'], 'shop_sessions_status_opened_at_index');

            // Index for user's session history
            // Used by: getActiveSession(), user session lookups
            $table->index(['user_id', 'status'], 'shop_sessions_user_status_index');
        });

        // ========================================
        // daily_consignments - Optimize for aggregation
        // ========================================
        Schema::table('daily_consignments', function (Blueprint $table) {
            // Composite index for session-based aggregation
            // Used by: calculateClosingSummary(), revenue calculations
            $table->index(['shop_session_id', 'partner_id'], 'daily_consignments_session_partner_index');

            // Index on created_at for time-based queries
            $table->index('created_at', 'daily_consignments_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('box_orders', function (Blueprint $table) {
            $table->dropIndex('box_orders_created_at_index');
            $table->dropIndex('box_orders_status_created_at_index');
            $table->dropIndex('box_orders_status_pickup_index');
        });

        Schema::table('shop_sessions', function (Blueprint $table) {
            $table->dropIndex('shop_sessions_status_opened_at_index');
            $table->dropIndex('shop_sessions_user_status_index');
        });

        Schema::table('daily_consignments', function (Blueprint $table) {
            $table->dropIndex('daily_consignments_session_partner_index');
            $table->dropIndex('daily_consignments_created_at_index');
        });
    }
};
