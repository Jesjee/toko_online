<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router, Head } from '@inertiajs/vue3';

defineProps({ 
    products: Object,
    status: String,
});

const formatRupiah = (n) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
}).format(n);

const hapus = (id) => {
    if (confirm('Hapus produk ini?'))
        router.delete(route('seller.products.destroy', id));
};

// Filter status
const filterStatus = (e) => {
    router.get(route('seller.products.index'), { status: e.target.value }, { preserveState: true });
};
</script>

<template>
    <AppLayout>
        <Head title="Produk Saya" />
        <div class="max-w-6xl mx-auto px-4 py-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">Produk Saya</h1>
                <div class="flex gap-3 items-center">
                    <!-- Filter Dropdown -->
                    <select @change="filterStatus" class="input text-sm" :value="status || ''">
                        <option value="">Semua</option>
                        <option value="active">Aktif</option>
                        <option value="draft">Draft</option>
                        <option value="inactive">Nonaktif</option>
                    </select>
                    <!-- Link ke Trash -->
                    <Link :href="route('seller.products.trash')" class="text-sm text-red-500 hover:underline">
                        🗑 Produk Terhapus
                    </Link>
                    <Link :href="route('seller.products.create')" class="btn-primary">
                        + Tambah Produk
                    </Link>
                </div>
            </div>

            <div class="card overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-orange-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left">Produk</th>
                            <th class="px-4 py-3 text-right">Harga</th>
                            <th class="px-4 py-3 text-center">Stok</th>
                            <th class="px-4 py-3 text-center">Status</th>
                            <th class="px-4 py-3 text-center">Views</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="product in products.data" :key="product.id"
                            class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <img :src="product.image ? '/storage/'+product.image : '/img/no-image.png'"
                                         class="w-12 h-12 object-cover rounded-lg" />
                                    <div>
                                        <p class="font-medium">{{ product.name }}</p>
                                        <p class="text-xs text-gray-400">{{ product.slug }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-right font-mono">{{ formatRupiah(product.price) }}</td>
                            <td class="px-4 py-3 text-center">{{ product.stock }}</td>
                            <td class="px-4 py-3 text-center">
                                <span :class="{
                                    'bg-green-100 text-green-700'  : product.status === 'active',
                                    'bg-gray-100  text-gray-500'   : product.status === 'inactive',
                                    'bg-yellow-100 text-yellow-700': product.status === 'draft',
                                }" class="px-2 py-1 rounded-full text-xs font-medium">
                                    {{ product.status }}
                                </span>
                            </td>
                            <!-- Kolom Views -->
                            <td class="px-4 py-3 text-center text-gray-500">
                                👁 {{ product.views ?? 0 }}
                            </td>
                            <td class="px-4 py-3 text-center space-x-2">
                                <Link :href="route('seller.products.edit', product.id)"
                                      class="text-blue-600 hover:underline text-xs">Edit</Link>
                                <button @click="hapus(product.id)"
                                        class="text-red-600 hover:underline text-xs">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4 flex gap-2">
                <Link v-for="link in products.links" :key="link.label"
                      :href="link.url || '#'"
                      v-html="link.label"
                      :class="[link.active ? 'bg-orange-600 text-white' : 'bg-white text-gray-700',
                               'px-3 py-1 border rounded text-sm']" />
            </div>
        </div>
    </AppLayout>
</template>