<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head } from '@inertiajs/vue3';

defineProps({
    totalProduk:   Number,
    totalPenjual:  Number,
    categories:    Array,
    produkTerbaru: Array,
});

const rupiah = (n) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
}).format(n);
</script>

<template>
    <AppLayout>
        <Head title="Beranda" />

        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-indigo-700 to-indigo-900 text-white">
            <div class="max-w-7xl mx-auto px-4 py-24 text-center">
                <h1 class="text-5xl font-bold mb-4">Selamat Datang di Toko Online</h1>
                <p class="text-indigo-200 text-xl mb-2">
                    {{ totalProduk }} produk dari {{ totalPenjual }} penjual terpercaya
                </p>
                <Link href="/products"
                    class="mt-8 inline-block bg-white text-indigo-700 font-bold px-8 py-3 rounded-xl hover:bg-indigo-50 transition-colors">
                    Lihat Semua Produk →
                </Link>
            </div>
        </div>

        <!-- Kartu Info -->
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card text-center">
                    <p class="text-4xl mb-3">📦</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ totalProduk }}</p>
                    <p class="text-gray-500 mt-1">Produk Tersedia</p>
                </div>
                <div class="card text-center">
                    <p class="text-4xl mb-3">🏪</p>
                    <p class="text-3xl font-bold text-indigo-600">{{ totalPenjual }}</p>
                    <p class="text-gray-500 mt-1">Penjual Aktif</p>
                </div>
            </div>
        </div>

        <!-- Grid Kategori -->
        <section class="max-w-7xl mx-auto px-4 py-12">
            <h2 class="text-2xl font-bold mb-6">Kategori Pilihan</h2>
            <div class="grid grid-cols-4 sm:grid-cols-8 gap-3">
                <Link v-for="cat in categories" :key="cat.id"
                    :href="'/products?category=' + cat.slug"
                    class="card text-center hover:shadow-md hover:border-indigo-300 transition-all cursor-pointer">
                    <span class="text-3xl">{{ cat.icon }}</span>
                    <p class="text-xs mt-2 text-gray-600">{{ cat.name }}</p>
                </Link>
            </div>
        </section>

        <!-- Produk Terbaru -->
        <section class="max-w-7xl mx-auto px-4 pb-16">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Produk Terbaru</h2>
                <Link href="/products" class="text-indigo-600 hover:underline">Lihat Semua →</Link>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <Link v-for="prod in produkTerbaru" :key="prod.id"
                    :href="route('products.show', prod.slug)"
                    class="card hover:shadow-lg transition-shadow">
                    <img :src="prod.image ? '/storage/'+prod.image : '/img/no-image.png'"
                         class="w-full h-36 object-cover rounded-lg mb-3" />
                    <h3 class="font-semibold text-sm line-clamp-2">{{ prod.name }}</h3>
                    <p class="text-indigo-600 font-bold text-sm mt-1">{{ rupiah(prod.price) }}</p>
                </Link>
            </div>
        </section>

    </AppLayout>
</template>