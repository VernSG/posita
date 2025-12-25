<script setup>
import EmployeeLayout from '@/Layouts/EmployeeLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref, reactive, computed, watch } from 'vue';
import { formatMoney } from '@/utils/formatMoney';

const props = defineProps({
    selectedTemplate: {
        type: Object,
        default: null,
    },
    boxTemplates: {
        type: Array,
        default: () => [],
    },
});

// Selected template ID for dropdown
const selectedTemplateId = ref('');

const form = useForm({
    customer_name: '',
    pickup_datetime: '',
    quantity: 1, // Number of boxes to order
    items: [],
});

// Watch for template selection and auto-fill items
watch(selectedTemplateId, (newId) => {
    if (!newId) return;
    
    const template = props.boxTemplates.find(t => t.id === parseInt(newId));
    if (template && template.items_json) {
        // Clear existing items and add template items
        form.items = [];
        
        // items_json is an array of item names, we need to create item objects
        const itemNames = Array.isArray(template.items_json) 
            ? template.items_json 
            : JSON.parse(template.items_json);
            
        itemNames.forEach(itemName => {
            form.items.push({
                product_name: itemName,
                quantity: 1,
                unit_price: Math.round(template.price / itemNames.length), // Distribute price evenly
            });
        });
    }
});

// Add a new line item
const addItem = () => {
    form.items.push({
        product_name: '',
        quantity: 1,
        unit_price: '',
    });
};

// Remove a line item
const removeItem = (index) => {
    form.items.splice(index, 1);
};

// Calculate subtotal (items only)
const itemsSubtotal = computed(() => {
    return form.items.reduce((sum, item) => {
        return sum + (item.quantity * (item.unit_price || 0));
    }, 0);
});

// Calculate grand total (items * box quantity)
const calculatedTotal = computed(() => {
    return itemsSubtotal.value * (form.quantity || 1);
});

// Get minimum datetime (now + 1 hour)
const minDateTime = computed(() => {
    const now = new Date();
    now.setHours(now.getHours() + 1);
    return now.toISOString().slice(0, 16);
});

const submit = () => {
    form.post('/pos/box', {
        onSuccess: () => {
            form.reset();
        },
    });
};

// Clear template selection (for custom order)
const clearTemplate = () => {
    selectedTemplateId.value = '';
    form.items = [];
    form.quantity = 1;
    addItem();
};

// Initialize with one empty item
if (form.items.length === 0) {
    addItem();
}
</script>

<template>
    <Head title="Buat Order Box" />

    <EmployeeLayout>
        <template #header>
            <h2 class="text-lg font-semibold text-gray-800">Buat Order Box</h2>
        </template>

        <div class="max-w-lg mx-auto">
            <div class="bg-white rounded-lg shadow p-6">
                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Template Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Template Box (Opsional)
                        </label>
                        <div class="flex gap-2">
                            <select
                                v-model="selectedTemplateId"
                                class="flex-1 border rounded-lg px-3 py-2 text-sm"
                            >
                                <option value="">-- Pilih Template atau Input Manual --</option>
                                <optgroup label="Heavy Meal">
                                    <option 
                                        v-for="template in boxTemplates.filter(t => t.type === 'heavy_meal')" 
                                        :key="template.id" 
                                        :value="template.id"
                                    >
                                        {{ template.name }} - {{ formatMoney(template.price) }}
                                    </option>
                                </optgroup>
                                <optgroup label="Snack Box">
                                    <option 
                                        v-for="template in boxTemplates.filter(t => t.type === 'snack_box')" 
                                        :key="template.id" 
                                        :value="template.id"
                                    >
                                        {{ template.name }} - {{ formatMoney(template.price) }}
                                    </option>
                                </optgroup>
                            </select>
                            <button
                                v-if="selectedTemplateId"
                                type="button"
                                @click="clearTemplate"
                                class="px-3 py-2 text-sm text-gray-600 hover:text-red-600"
                                title="Reset ke input manual"
                            >
                                ✕
                            </button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Pilih template untuk auto-fill, atau biarkan kosong untuk input manual
                        </p>
                    </div>

                    <!-- Customer Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Pelanggan
                        </label>
                        <input
                            v-model="form.customer_name"
                            type="text"
                            class="w-full border rounded-lg px-3 py-2"
                            placeholder="Masukkan nama pelanggan"
                            required
                        />
                        <p v-if="form.errors.customer_name" class="text-red-500 text-sm mt-1">
                            {{ form.errors.customer_name }}
                        </p>
                    </div>

                    <!-- Box Quantity -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Jumlah Box
                        </label>
                        <div class="flex items-center gap-3">
                            <button
                                type="button"
                                @click="form.quantity = Math.max(1, form.quantity - 1)"
                                class="w-10 h-10 bg-gray-100 rounded-lg text-xl font-bold hover:bg-gray-200"
                            >
                                −
                            </button>
                            <input
                                v-model.number="form.quantity"
                                type="number"
                                min="1"
                                class="w-20 border rounded-lg px-3 py-2 text-center text-lg font-semibold"
                                required
                            />
                            <button
                                type="button"
                                @click="form.quantity++"
                                class="w-10 h-10 bg-gray-100 rounded-lg text-xl font-bold hover:bg-gray-200"
                            >
                                +
                            </button>
                            <span class="text-sm text-gray-500">box</span>
                        </div>
                        <p v-if="form.errors.quantity" class="text-red-500 text-sm mt-1">
                            {{ form.errors.quantity }}
                        </p>
                    </div>

                    <!-- Pickup DateTime -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal & Waktu Pengambilan
                        </label>
                        <input
                            v-model="form.pickup_datetime"
                            type="datetime-local"
                            :min="minDateTime"
                            class="w-full border rounded-lg px-3 py-2"
                            required
                        />
                        <p v-if="form.errors.pickup_datetime" class="text-red-500 text-sm mt-1">
                            {{ form.errors.pickup_datetime }}
                        </p>
                    </div>

                    <!-- Line Items -->
                    <div>
                        <div class="flex justify-between items-center mb-3">
                            <label class="block text-sm font-medium text-gray-700">
                                Item per Box
                                <span v-if="selectedTemplateId" class="text-xs text-blue-600 ml-1">
                                    (dari template - bisa diedit)
                                </span>
                            </label>
                            <button
                                type="button"
                                @click="addItem"
                                class="text-sm bg-green-100 text-green-700 px-3 py-1 rounded-lg hover:bg-green-200"
                            >
                                + Tambah Item
                            </button>
                        </div>

                        <div class="space-y-3">
                            <div
                                v-for="(item, index) in form.items"
                                :key="index"
                                class="bg-gray-50 rounded-lg p-3"
                            >
                                <div class="flex items-start gap-2">
                                    <div class="flex-1 grid grid-cols-3 gap-2">
                                        <div class="col-span-3 sm:col-span-1">
                                            <label class="block text-xs text-gray-500 mb-1">Nama</label>
                                            <input
                                                v-model="item.product_name"
                                                type="text"
                                                class="w-full border rounded px-2 py-1 text-sm"
                                                placeholder="Nasi"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Qty/Box</label>
                                            <input
                                                v-model.number="item.quantity"
                                                type="number"
                                                min="1"
                                                class="w-full border rounded px-2 py-1 text-sm text-center"
                                                required
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-xs text-gray-500 mb-1">Harga</label>
                                            <div class="relative">
                                                <span class="absolute left-2 top-1/2 -translate-y-1/2 text-gray-400 text-xs">Rp</span>
                                                <input
                                                    v-model.number="item.unit_price"
                                                    type="number"
                                                    min="0"
                                                    class="w-full border rounded pl-7 pr-2 py-1 text-sm"
                                                    required
                                                />
                                            </div>
                                        </div>
                                    </div>
                                    <button
                                        v-if="form.items.length > 1"
                                        type="button"
                                        @click="removeItem(index)"
                                        class="text-red-500 hover:text-red-700 mt-5"
                                    >
                                        ✕
                                    </button>
                                </div>
                                <div class="mt-2 text-right text-xs text-gray-500">
                                    Subtotal: {{ formatMoney(item.quantity * (item.unit_price || 0)) }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Summary -->
                    <div class="bg-blue-50 rounded-lg p-4 space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Harga per Box</span>
                            <span class="font-medium">{{ formatMoney(itemsSubtotal) }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <span class="text-gray-600">Jumlah Box</span>
                            <span class="font-medium">× {{ form.quantity }}</span>
                        </div>
                        <div class="border-t pt-2 flex justify-between items-center">
                            <span class="font-medium text-gray-700">Total</span>
                            <span class="text-xl font-bold text-blue-600">
                                {{ formatMoney(calculatedTotal) }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-3 pt-4">
                        <Link
                            href="/pos/box"
                            class="flex-1 text-center border border-gray-300 text-gray-700 py-3 rounded-lg hover:bg-gray-50"
                        >
                            Batal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing || form.items.length === 0"
                            class="flex-1 bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 disabled:opacity-50"
                        >
                            {{ form.processing ? 'Menyimpan...' : 'Buat Order' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </EmployeeLayout>
</template>
