<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    user: Object,
    totalPesanan: Number,
    pesananPending: Number,
    totalBelanjaBulanIni: Number,
});

const rupiah = (n) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
}).format(n);
</script>

<template>
    <AppLayout>
        <Head title="Dashboard" />
        <div class="max-w-4xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-8">🛍️ Dashboard Buyer</h1>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Pesanan -->
                <div class="card text-center">
                    <p class="text-4xl mb-2">📋</p>
                    <p class="text-3xl font-bold text-teal-700">{{ totalPesanan }}</p>
                    <p class="text-gray-500 text-sm mt-1">Total Pesanan</p>
                </div>

                <!-- Pesanan Pending -->
                <div class="card text-center">
                    <p class="text-4xl mb-2">🕐</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ pesananPending }}</p>
                    <p class="text-gray-500 text-sm mt-1">Menunggu Pembayaran</p>
                </div>

                <!-- Total Belanja Bulan Ini -->
                <div class="card text-center">
                    <p class="text-4xl mb-2">💰</p>
                    <p class="text-2xl font-bold text-blue-600">{{ rupiah(totalBelanjaBulanIni) }}</p>
                    <p class="text-gray-500 text-sm mt-1">Belanja Bulan Ini</p>
                </div>
            </div>

            <div class="text-center">
                <Link href="/orders" class="btn-primary px-6 py-2 inline-block">
                    Lihat Semua Pesanan →
                </Link>
            </div>
        </div>
    </AppLayout>
</template>