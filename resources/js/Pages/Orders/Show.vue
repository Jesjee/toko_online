<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ order: Object });

const rupiah = (n) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
}).format(n);

const statusClass = computed(() => ({
    pending:    'bg-yellow-100 text-yellow-700',
    paid:       'bg-blue-100 text-blue-700',
    processing: 'bg-purple-100 text-purple-700',
    shipped:    'bg-teal-100 text-teal-700',
    delivered:  'bg-green-100 text-green-700',
    cancelled:  'bg-red-100 text-red-700',
}[props.order.status]));

const cancelForm = useForm({});
const cancel = () => {
    if (confirm('Yakin ingin membatalkan pesanan ini?')) {
        cancelForm.post(route('orders.cancel', props.order.id));
    }
};

// ── P9: Timeline Status ──────────────────────────────────────────
const statusOrder = ['pending', 'paid', 'processing', 'shipped', 'delivered'];
const isReached = (status) => {
    const current = statusOrder.indexOf(props.order.status);
    const target  = statusOrder.indexOf(status);
    return current >= target;
};
const statusTimeline = [
    { status: 'pending',    icon: '🕐', label: 'Pesanan dibuat' },
    { status: 'paid',       icon: '💳', label: 'Pembayaran dikirim' },
    { status: 'processing', icon: '📦', label: 'Dikonfirmasi & Diproses' },
    { status: 'shipped',    icon: '🚚', label: 'Paket dikirim' },
    { status: 'delivered',  icon: '✅', label: 'Pesanan diterima' },
];
</script>

<template>
    <AppLayout>
        <Head :title="order.order_number" />
        <div class="max-w-3xl mx-auto px-4 py-8">

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <Link href="/orders" class="text-gray-400 hover:text-gray-600 text-sm">
                        ← Kembali ke Pesanan
                    </Link>
                    <h1 class="text-xl font-bold font-mono mt-1">{{ order.order_number }}</h1>
                </div>
                <span :class="[statusClass, 'px-4 py-2 rounded-full font-medium']">
                    {{ order.status }}
                </span>
            </div>

            <!-- Tabel Item -->
            <div class="card mb-6">
                <h2 class="font-bold mb-4">📦 Item Pesanan</h2>
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-3 py-2 text-left">Produk</th>
                            <th class="px-3 py-2 text-right">Harga</th>
                            <th class="px-3 py-2 text-center">Qty</th>
                            <th class="px-3 py-2 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in order.items" :key="item.id" class="border-b">
                            <td class="px-3 py-3">
                                <div class="flex items-center gap-3">
                                    <img :src="item.product_image
                                        ? '/storage/'+item.product_image
                                        : '/img/no-image.png'"
                                        class="w-10 h-10 object-cover rounded" />
                                    <span>{{ item.product_name }}</span>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-right">{{ rupiah(item.price) }}</td>
                            <td class="px-3 py-3 text-center">{{ item.qty }}</td>
                            <td class="px-3 py-3 text-right font-bold">
                                {{ rupiah(item.price * item.qty) }}
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr class="bg-gray-50">
                            <td colspan="3" class="px-3 py-3 font-bold text-right">Total</td>
                            <td class="px-3 py-3 text-right font-bold text-teal-700 text-lg">
                                {{ rupiah(order.total_amount) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Info Pengiriman -->
            <div class="card mb-6">
                <h2 class="font-bold mb-4">📍 Informasi Pengiriman</h2>
                <p class="text-gray-700">{{ order.shipping_address }}</p>
                <p class="text-gray-500 text-sm mt-1">📞 {{ order.phone }}</p>
                <p v-if="order.notes" class="text-gray-500 text-sm mt-1">
                    📝 {{ order.notes }}
                </p>
            </div>

            <!-- P9: Tombol Upload Bukti Bayar (pending) -->
            <div v-if="order.status === 'pending'"
                class="card bg-yellow-50 border-yellow-200 mb-4">
                <h3 class="font-bold text-yellow-800 mb-2">⏳ Menunggu Pembayaran</h3>
                <p class="text-sm text-yellow-700 mb-4">
                    Segera transfer dan upload bukti pembayaran.
                </p>
                <Link :href="route('orders.payment', order.id)"
                    class="block w-full text-center bg-rose-600 text-white py-2 rounded-lg font-bold hover:bg-rose-700">
                    💳 Upload Bukti Pembayaran
                </Link>
            </div>

            <!-- Tombol Batalkan -->
            <div v-if="order.status === 'pending'" class="text-center mb-6">
                <button @click="cancel" :disabled="cancelForm.processing"
                    class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg font-medium">
                    ❌ Batalkan Pesanan
                </button>
            </div>

            <!-- P9: Timeline Status -->
            <div class="card mt-6">
                <h3 class="font-bold mb-4">📍 Status Pesanan</h3>
                <div class="space-y-3">
                    <div v-for="step in statusTimeline" :key="step.status"
                        class="flex items-center gap-3 p-3 rounded-lg transition-all"
                        :class="isReached(step.status)
                            ? 'bg-green-50 border border-green-200'
                            : 'bg-gray-50 opacity-40'">
                        <span class="text-xl">{{ step.icon }}</span>
                        <span class="font-medium flex-1">{{ step.label }}</span>
                        <span v-if="isReached(step.status)" class="text-green-600 font-bold">✓</span>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>