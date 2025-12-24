<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { formatMoney } from '@/utils/formatMoney';

const props = defineProps({
    dailySalesTotal: {
        type: Number,
        default: 0,
    },
    pendingBoxOrders: {
        type: Array,
        default: () => [],
    },
    boxOrderStats: {
        type: Object,
        default: () => ({}),
    },
    todaySessions: {
        type: Array,
        default: () => [],
    },
    quickStats: {
        type: Object,
        default: () => ({}),
    },
    weeklyRevenue: {
        type: Array,
        default: () => [],
    },
    monthlyRevenue: {
        type: Array,
        default: () => [],
    },
});

const chartTab = ref('weekly');

// Calculate max value for chart scaling
const weeklyMax = computed(() => Math.max(...props.weeklyRevenue.map(d => d.revenue), 1));
const monthlyMax = computed(() => Math.max(...props.monthlyRevenue.map(d => d.revenue), 1));

const getBarHeight = (value, max) => {
    return Math.max((value / max) * 100, 2) + '%';
};
</script>

<template>
    <Head title="Admin Dashboard" />

    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
        </template>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Penjualan Hari Ini</h3>
                <p class="text-2xl font-bold text-green-600 mt-2">
                    {{ formatMoney(dailySalesTotal) }}
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Order Box Pending</h3>
                <p class="text-2xl font-bold text-orange-600 mt-2">
                    {{ boxOrderStats.pending_orders || 0 }}
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Total Partners</h3>
                <p class="text-2xl font-bold text-blue-600 mt-2">
                    {{ quickStats.total_partners || 0 }}
                </p>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-sm font-medium text-gray-500">Total Karyawan</h3>
                <p class="text-2xl font-bold text-purple-600 mt-2">
                    {{ quickStats.total_employees || 0 }}
                </p>
            </div>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">📈 Trend Penjualan</h3>
                <div class="flex gap-2">
                    <button
                        @click="chartTab = 'weekly'"
                        :class="[
                            'px-4 py-1 rounded-full text-sm transition-colors',
                            chartTab === 'weekly'
                                ? 'bg-blue-600 text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                        ]"
                    >
                        Mingguan
                    </button>
                    <button
                        @click="chartTab = 'monthly'"
                        :class="[
                            'px-4 py-1 rounded-full text-sm transition-colors',
                            chartTab === 'monthly'
                                ? 'bg-blue-600 text-white'
                                : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                        ]"
                    >
                        Bulanan
                    </button>
                </div>
            </div>
            <div class="p-6">
                <!-- Weekly Chart -->
                <div v-if="chartTab === 'weekly'" class="h-64 flex items-end justify-between gap-2">
                    <div
                        v-for="(day, idx) in weeklyRevenue"
                        :key="idx"
                        class="flex-1 flex flex-col items-center"
                    >
                        <div class="w-full flex flex-col items-center justify-end h-48">
                            <div
                                class="w-full max-w-12 bg-gradient-to-t from-blue-600 to-blue-400 rounded-t-lg transition-all duration-300 relative group"
                                :style="{ height: getBarHeight(day.revenue, weeklyMax) }"
                            >
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap">
                                    {{ formatMoney(day.revenue) }}
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-gray-600 mt-2">{{ day.date }}</span>
                    </div>
                </div>

                <!-- Monthly Chart -->
                <div v-else class="h-64 flex items-end justify-between gap-4">
                    <div
                        v-for="(month, idx) in monthlyRevenue"
                        :key="idx"
                        class="flex-1 flex flex-col items-center"
                    >
                        <div class="w-full flex flex-col items-center justify-end h-48">
                            <div
                                class="w-full max-w-16 bg-gradient-to-t from-green-600 to-green-400 rounded-t-lg transition-all duration-300 relative group"
                                :style="{ height: getBarHeight(month.revenue, monthlyMax) }"
                            >
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 whitespace-nowrap">
                                    {{ formatMoney(month.revenue) }}
                                </div>
                            </div>
                        </div>
                        <span class="text-xs text-gray-600 mt-2">{{ month.month }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Today's Sessions -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">Sesi Toko Hari Ini</h3>
                    <a
                        href="/admin/reports/daily"
                        target="_blank"
                        class="text-sm text-blue-600 hover:text-blue-800"
                    >
                        📥 Download PDF
                    </a>
                </div>
                <div class="p-6">
                    <div v-if="todaySessions.length === 0" class="text-gray-500 text-center py-8">
                        Belum ada sesi toko hari ini
                    </div>
                    <div v-else class="space-y-3">
                        <div
                            v-for="session in todaySessions"
                            :key="session.id"
                            class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
                        >
                            <div>
                                <p class="font-medium">{{ session.user?.name }}</p>
                                <p class="text-sm text-gray-500">
                                    {{ session.opened_at }}
                                </p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span
                                    :class="[
                                        'px-2 py-1 text-xs rounded-full',
                                        session.status === 'open'
                                            ? 'bg-green-100 text-green-800'
                                            : 'bg-gray-100 text-gray-800'
                                    ]"
                                >
                                    {{ session.status === 'open' ? 'Aktif' : 'Tutup' }}
                                </span>
                                <a
                                    v-if="session.status === 'closed'"
                                    :href="`/admin/reports/session/${session.id}`"
                                    target="_blank"
                                    class="text-blue-600 hover:text-blue-800 text-sm"
                                >
                                    📄
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pending Box Orders -->
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b">
                    <h3 class="text-lg font-semibold text-gray-800">Order Box Pending</h3>
                </div>
                <div class="p-6">
                    <div v-if="pendingBoxOrders.length === 0" class="text-gray-500 text-center py-8">
                        Tidak ada order pending
                    </div>
                    <div v-else class="space-y-3">
                        <div
                            v-for="order in pendingBoxOrders"
                            :key="order.id"
                            class="flex items-center justify-between p-3 bg-orange-50 rounded-lg"
                        >
                            <div>
                                <p class="font-medium">{{ order.customer_name }}</p>
                                <p class="text-sm text-gray-600">
                                    {{ order.template?.name }} x {{ order.quantity }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-orange-600">
                                    {{ formatMoney(order.total_price) }}
                                </p>
                                <p class="text-xs text-gray-500">{{ order.pickup_datetime }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box Order Stats -->
        <div class="mt-6 bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">📦 Statistik Box Order</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-2xl font-bold text-blue-600">{{ boxOrderStats.today_orders || 0 }}</p>
                    <p class="text-sm text-gray-600">Order Hari Ini</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-2xl font-bold text-green-600">{{ formatMoney(boxOrderStats.today_revenue || 0) }}</p>
                    <p class="text-sm text-gray-600">Revenue Hari Ini</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <p class="text-2xl font-bold text-purple-600">{{ boxOrderStats.month_orders || 0 }}</p>
                    <p class="text-sm text-gray-600">Order Bulan Ini</p>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <p class="text-2xl font-bold text-orange-600">{{ formatMoney(boxOrderStats.month_revenue || 0) }}</p>
                    <p class="text-sm text-gray-600">Revenue Bulan Ini</p>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
