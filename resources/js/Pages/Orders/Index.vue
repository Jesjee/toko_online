<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, router } from '@inertiajs/vue3';

defineProps({ orders: Object, status: String });

const rupiah = (n) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
}).format(n);

const statusClass = (status) => ({
    pending:    'bg-yellow-100 text-yellow-700 border-yellow-300',
    paid:       'bg-blue-100 text-blue-700 border-blue-300',
    processing: 'bg-purple-100 text-purple-700 border-purple-300',
    shipped:    'bg-teal-100 text-teal-700 border-teal-300',
    delivered:  'bg-green-100 text-green-700 border-green-300',
    cancelled:  'bg-red-100 text-red-700 border-red-300',
}[status] || 'bg-gray-100 text-gray-600');

const statusLabel = (status) => ({
    pending:    '🕐 Menunggu Pembayaran',
    paid:       '💳 Sudah Dibayar',
    processing: '📦 Diproses',
    shipped:    '🚚 Dikirim',
    delivered:  '✅ Diterima',
    cancelled:  '❌ Dibatalkan',
}[status] || status);

const tabs = [
    { label: 'Semua', value: '' },
    { label: 'Pending', value: 'pending' },
    { label: 'Dibayar', value: 'paid' },
    { label: 'Dikirim', value: 'shipped' },
    { label: 'Diterima', value: 'delivered' },
];

const filterStatus = (val) => {
    router.get(route('orders.index'), val ? { status: val } : {}, { preserveState: true });
};
</script>

<template>
    <AppLayout>
        <Head title="Pesanan Saya" />
        <div class="max-w-4xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6">📋 Pesanan Saya</h1>

            <!-- Filter Tab -->
            <div class="flex gap-2 mb-6 flex-wrap">
                <button v-for="tab in tabs" :key="tab.value"
                    @click="filterStatus(tab.value)"
                    :class="[status === tab.value || (tab.value === '' && !status)
                        ? 'bg-teal-600 text-white'
                        : 'bg-white text-gray-600 border',
                        'px-4 py-1.5 rounded-full text-sm font-medium']">
                    {{ tab.label }}
                </button>
            </div>

            <div v-if="orders.data.length === 0" class="text-center py-20 text-gray-400">
                <p class="text-5xl mb-4">📭</p>
                <p class="text-lg">Belum ada pesanan.</p>
                <Link href="/products" class="text-teal-600 hover:underline mt-2 block">
                    Mulai Belanja →
                </Link>
            </div>

            <div v-else class="space-y-4">
                <Link v-for="order in orders.data" :key="order.id"
                    :href="route('orders.show', order.id)"
                    class="card block hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold font-mono text-sm text-gray-600">
                                {{ order.order_number }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">
                                {{ new Date(order.created_at).toLocaleDateString('id-ID', {
                                    day: 'numeric', month: 'long', year: 'numeric'
                                }) }}
                                · {{ order.items?.length || 0 }} item
                            </p>
                        </div>
                        <span :class="[statusClass(order.status),
                            'px-3 py-1 rounded-full text-xs font-medium border']">
                            {{ statusLabel(order.status) }}
                        </span>
                    </div>
                    <div class="mt-3 flex justify-between items-center">
                        <p class="text-gray-500 text-sm">Total</p>
                        <p class="font-bold text-teal-700">{{ rupiah(order.total_amount) }}</p>
                    </div>
                </Link>
            </div>

            <div class="mt-6 flex gap-2 justify-center">
                <Link v-for="link in orders.links" :key="link.label"
                    :href="link.url || '#'" v-html="link.label"
                    :class="[link.active ? 'bg-teal-600 text-white' : 'bg-white text-gray-700',
                        'px-3 py-1 border rounded text-sm']" />
            </div>
        </div>
    </AppLayout>
</template>