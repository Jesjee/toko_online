<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

defineProps({ orders: Object });

const rupiah = (n) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
}).format(n);

const badgeClass = (status) => ({
    pending:    'bg-yellow-100 text-yellow-700 border-yellow-300',
    paid:       'bg-blue-100 text-blue-700 border-blue-300',
    processing: 'bg-purple-100 text-purple-700 border-purple-300',
    shipped:    'bg-teal-100 text-teal-700 border-teal-300',
    delivered:  'bg-green-100 text-green-700 border-green-300',
    cancelled:  'bg-red-100 text-red-700 border-red-300',
}[status] || 'bg-gray-100 text-gray-600');
</script>

<template>
    <AppLayout>
        <Head title="Pesanan Masuk" />
        <div class="max-w-5xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-6">📬 Pesanan Masuk</h1>

            <div class="bg-white border rounded-xl shadow-sm overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-rose-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Nomor Pesanan</th>
                            <th class="px-4 py-3 text-left">Pembeli</th>
                            <th class="px-4 py-3 text-right">Total</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="order in orders.data" :key="order.id"
                            class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono text-xs">{{ order.order_number }}</td>
                            <td class="px-4 py-3">{{ order.buyer?.name }}</td>
                            <td class="px-4 py-3 text-right">{{ rupiah(order.total_amount) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span :class="[badgeClass(order.status),
                                    'px-2 py-1 rounded-full text-xs border']">
                                    {{ order.status }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <Link :href="route('seller.orders.show', order.id)"
                                    class="text-rose-600 hover:underline text-xs">
                                    Detail →
                                </Link>
                            </td>
                        </tr>
                        <tr v-if="!orders.data.length">
                            <td colspan="5" class="text-center py-12 text-gray-400">
                                Belum ada pesanan masuk.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>