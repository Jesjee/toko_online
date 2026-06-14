<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

const props = defineProps({ order: Object });

const rupiah = (n) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
}).format(n);
</script>

<template>
    <AppLayout>
        <Head :title="'Order ' + order.order_number" />
        <div class="max-w-3xl mx-auto px-4 py-8">

            <Link :href="route('seller.orders.index')"
                class="text-gray-400 text-sm">← Kembali ke Pesanan Masuk</Link>

            <div class="flex justify-between items-center mt-2 mb-6">
                <h1 class="text-xl font-bold font-mono">{{ order.order_number }}</h1>
                <span class="px-3 py-1 rounded-full text-sm font-medium
                             bg-gray-100 text-gray-700">{{ order.status }}</span>
            </div>

            <!-- Tabel Item -->
            <div class="bg-white border rounded-xl p-6 shadow-sm mb-6">
                <h2 class="font-bold mb-3">Item Pesanan</h2>
                <table class="w-full text-sm">
                    <tr v-for="item in order.items" :key="item.id" class="border-b">
                        <td class="py-2">{{ item.product_name }}</td>
                        <td class="py-2 text-right">{{ rupiah(item.price) }} × {{ item.qty }}</td>
                        <td class="py-2 text-right font-bold">{{ rupiah(item.price * item.qty) }}</td>
                    </tr>
                    <tr class="bg-gray-50">
                        <td colspan="2" class="py-2 text-right font-bold">Total</td>
                        <td class="py-2 text-right font-bold text-rose-600">
                            {{ rupiah(order.total_amount) }}
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Bukti Bayar + Aksi (status: paid) -->
            <div v-if="order.status === 'paid'" class="bg-white border rounded-xl p-6 shadow-sm mb-4">
                <h2 class="font-bold mb-3">💳 Bukti Pembayaran</h2>
                <img :src="'/storage/' + order.payment_proof"
                    class="w-full max-w-xs rounded-xl border mb-4" />
                <div class="flex gap-3">
                    <Link :href="route('seller.orders.verify', order.id)"
                        method="post" as="button"
                        class="flex-1 bg-green-600 text-white py-2 px-4 rounded-lg
                               font-medium text-center hover:bg-green-700">
                        ✅ Konfirmasi Pembayaran
                    </Link>
                    <Link :href="route('seller.orders.reject', order.id)"
                        method="post" as="button"
                        class="flex-1 bg-red-100 text-red-700 py-2 px-4 rounded-lg
                               font-medium text-center hover:bg-red-200">
                        ❌ Tolak Bukti Bayar
                    </Link>
                </div>
            </div>

            <!-- Tandai Dikirim (status: processing) -->
            <div v-if="order.status === 'processing'" class="bg-white border rounded-xl p-6 shadow-sm mb-4">
                <h2 class="font-bold mb-3">📦 Siap Kirim</h2>
                <p class="text-gray-600 text-sm mb-4">
                    Pembayaran sudah dikonfirmasi. Kemas pesanan dan klik tombol di bawah setelah dikirim.
                </p>
                <Link :href="route('seller.orders.ship', order.id)"
                    method="post" as="button"
                    class="w-full bg-teal-600 text-white py-3 rounded-xl font-bold
                           text-center hover:bg-teal-700 block">
                    🚚 Tandai Sudah Dikirim
                </Link>
            </div>

            <!-- Alamat Pengiriman -->
            <div class="bg-white border rounded-xl p-6 shadow-sm">
                <h2 class="font-bold mb-3">📍 Alamat Pengiriman</h2>
                <p class="text-gray-700">{{ order.shipping_address }}</p>
                <p class="text-gray-500 text-sm mt-1">📞 {{ order.phone }}</p>
            </div>

        </div>
    </AppLayout>
</template>