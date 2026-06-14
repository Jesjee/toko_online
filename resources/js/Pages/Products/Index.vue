<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Link, Head, router } from "@inertiajs/vue3";
import { ref, watch } from "vue";

const props = defineProps({
    products: Object,
    categories: Array,
    filters: Object,
});

const search   = ref(props.filters.search || "");
const category = ref(props.filters.category || "");
const sort     = ref(props.filters.sort || "latest");

const applyFilters = () => {
    router.get(
        route("products.index"),
        {
            search:   search.value || undefined,
            category: category.value || undefined,
            sort:     sort.value || undefined,
        },
        { preserveState: true },
    );
};

watch(category, applyFilters);
watch(sort, applyFilters);

const rupiah = (n) =>
    new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        maximumFractionDigits: 0,
    }).format(n);

// ─── Latihan 2: Badge BARU ────────────────────────────────────────
const isNew = (date) => {
    const diff = (new Date() - new Date(date)) / (1000 * 60 * 60 * 24);
    return diff <= 30;
};
</script>

<template>
    <AppLayout>
        <Head title="Katalog Produk" />
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold">Katalog Produk</h1>
                <p class="text-gray-500 mt-1">{{ products.total }} produk ditemukan</p>
            </div>

            <!-- Search + Sort -->
            <form @submit.prevent="applyFilters" class="flex gap-2 mb-6">
                <input
                    v-model="search"
                    type="text"
                    class="input flex-1"
                    placeholder="🔍 Cari produk..."
                />
                <select v-model="sort" class="input w-48">
                    <option value="latest">Terbaru</option>
                    <option value="price_asc">Harga Terendah</option>
                    <option value="price_desc">Harga Tertinggi</option>
                    <option value="popular">Terpopuler</option>
                </select>
                <button type="submit" class="btn-primary">Cari</button>
                <button
                    v-if="search || category"
                    type="button"
                    @click="search = ''; category = ''; sort = 'latest'; applyFilters();"
                    class="btn-secondary"
                >
                    Reset
                </button>
            </form>

            <!-- Filter Chip Kategori -->
            <div class="flex gap-2 flex-wrap mb-8">
                <button
                    @click="category = ''"
                    :class="!category ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300'"
                    class="px-4 py-1.5 rounded-full border text-sm font-medium transition-colors"
                >
                    Semua
                </button>
                <button
                    v-for="cat in categories"
                    :key="cat.id"
                    @click="category = cat.slug"
                    :class="category === cat.slug ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300'"
                    class="px-4 py-1.5 rounded-full border text-sm transition-colors"
                >
                    {{ cat.icon }} {{ cat.name }}
                </button>
            </div>

            <!-- Grid Produk -->
            <div
                v-if="products.data.length > 0"
                class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4"
            >
                <Link
                    v-for="prod in products.data"
                    :key="prod.id"
                    :href="route('products.show', prod.slug)"
                    class="card hover:shadow-lg transition-shadow group relative"
                >
                    <!-- Badge BARU di luar div foto -->
                    <span v-if="isNew(prod.created_at)"
                        class="absolute top-2 left-2 z-10 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                        BARU
                    </span>
                    <div class="w-full h-48 rounded-lg overflow-hidden mb-3">
                        <img
                            :src="prod.image ? '/storage/' + prod.image : '/img/no-image.png'"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                        />
                    </div>
                    <h3 class="font-semibold text-sm line-clamp-2 mb-1">{{ prod.name }}</h3>
                    <p class="text-indigo-600 font-bold">{{ rupiah(prod.price) }}</p>
                    <p class="text-xs text-gray-400 mt-1">
                        Stok: {{ prod.stock }} · {{ prod.reviews_count }} ulasan
                    </p>
                </Link>
            </div>

            <!-- Kosong -->
            <div v-else class="text-center py-20 text-gray-400">
                <p class="text-5xl mb-4">😔</p>
                <p class="text-lg">Produk tidak ditemukan.</p>
                <p class="text-sm">Coba ubah filter atau kata pencarian.</p>
            </div>

            <!-- Pagination -->
            <div class="mt-10 flex gap-2 justify-center flex-wrap">
                <Link
                    v-for="link in products.links"
                    :key="link.label"
                    :href="link.url || '#'"
                    v-html="link.label"
                    :class="[
                        link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700',
                        link.url ? '' : 'opacity-50 cursor-not-allowed',
                        'px-4 py-2 border rounded-lg text-sm transition-colors',
                    ]"
                />
            </div>
        </div>
    </AppLayout>
</template>