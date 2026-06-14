<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    product:    Object,
    produkLain: Array,
});

const auth   = computed(() => usePage().props.auth?.user);
const rupiah = (n) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
}).format(n);
</script>

<template>
    <AppLayout>
        <Head :title="product.name" />
        <div class="max-w-6xl mx-auto px-4 py-8">

            <!-- Breadcrumb -->
            <nav class="text-sm text-gray-500 mb-6 flex gap-2">
                <Link href="/" class="hover:text-indigo-600">Beranda</Link>
                <span>/</span>
                <Link href="/products" class="hover:text-indigo-600">Katalog</Link>
                <span>/</span>
                <span class="text-gray-800">{{ product.name }}</span>
            </nav>

            <!-- Layout 2 Kolom -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                <div class="flex items-center justify-center p-8 border rounded-2xl">
                    <img :src="product.image ? '/storage/'+product.image : '/img/no-image.png'"
                         class="max-h-80 max-w-full object-contain" />
                </div>
                <div class="space-y-5">
                    <!-- Kategori badges -->
                    <div class="flex gap-2 flex-wrap">
                        <Link v-for="cat in product.categories" :key="cat.id"
                            :href="'/products?category=' + cat.slug"
                            class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm hover:bg-indigo-200 transition-colors">
                            {{ cat.icon }} {{ cat.name }}
                        </Link>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ product.name }}</h1>
                    <p class="text-4xl font-bold text-indigo-600">{{ rupiah(product.price) }}</p>
                    <div class="flex gap-6 text-sm text-gray-500">
                        <span>Stok: <strong class="text-gray-800">{{ product.stock }}</strong> unit</span>
                        <span>Dilihat: <strong class="text-gray-800">{{ product.views }}</strong>x</span>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ product.description }}</p>

                    <!-- Info Seller -->
                    <div class="card bg-green-50 border-green-200">
                        <p class="text-sm text-gray-500">Dijual oleh</p>
                        <p class="font-semibold text-gray-800 text-lg">🏪 {{ product.seller?.name }}</p>
                    </div>

                    <div v-if="auth?.role === 'buyer'"
                        class="bg-gray-100 rounded-xl p-4 text-sm text-gray-500 text-center">
                        Fitur Keranjang akan diaktifkan di Pertemuan 10
                    </div>
                </div>
            </div>

            <!-- Ulasan -->
            <section class="mt-14">
                <h2 class="text-2xl font-bold mb-6">Ulasan Pembeli ({{ product.reviews?.length || 0 }})</h2>
                <p v-if="!product.reviews?.length" class="text-gray-400 py-8 text-center">
                    Belum ada ulasan untuk produk ini.
                </p>
                <div v-else class="space-y-4">
                    <div v-for="review in product.reviews" :key="review.id" class="card">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center font-bold text-indigo-600">
                                {{ review.user?.name?.charAt(0).toUpperCase() }}
                            </div>
                            <div>
                                <p class="font-semibold">{{ review.user?.name }}</p>
                                <div class="flex">
                                    <span v-for="i in 5" :key="i"
                                        :class="i <= review.rating ? 'text-yellow-400' : 'text-gray-200'">⭐</span>
                                </div>
                            </div>
                        </div>
                        <p class="text-gray-700">{{ review.comment }}</p>
                    </div>
                </div>
            </section>

            <!-- Produk Lain -->
            <section v-if="produkLain.length > 0" class="mt-14">
                <h2 class="text-2xl font-bold mb-6">Produk Lain dari Seller Ini</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <Link v-for="prod in produkLain" :key="prod.id"
                        :href="route('products.show', prod.slug)"
                        class="card hover:shadow-md transition-shadow">
                        <img :src="prod.image ? '/storage/'+prod.image : '/img/no-image.png'"
                             class="w-full h-32 object-cover rounded-lg mb-2" />
                        <h3 class="text-sm font-semibold line-clamp-2">{{ prod.name }}</h3>
                        <p class="text-indigo-600 font-bold text-sm">{{ rupiah(prod.price) }}</p>
                    </Link>
                </div>
            </section>

        </div>
    </AppLayout>
</template>