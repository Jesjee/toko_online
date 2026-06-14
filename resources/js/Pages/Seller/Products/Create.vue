<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    categories: Array
});

const form = useForm({
    name: '',
    description: '',
    price: 0,
    stock: 0,
    status: 'draft',
    image: null,           // File object
    category_ids: [],      // Array ID kategori yang dipilih
});

// Preview foto sebelum upload
const previewUrl = ref(null);

const onFileChange = (e) => {
    form.image = e.target.files[0];
    previewUrl.value = URL.createObjectURL(form.image);
};

// WAJIB: forceFormData agar file foto ikut terkirim
const submit = () => form.post(route('seller.products.store'), {
    forceFormData: true,
});
</script>

<template>
    <AppLayout>
        <Head title="Tambah Produk" />
        <div class="max-w-3xl mx-auto px-4 py-8">
            <div class="flex items-center gap-3 mb-6">
                <Link :href="route('seller.products.index')" class="text-gray-400 hover:text-gray-600">
                    ← Kembali
                </Link>
                <h1 class="text-2xl font-bold">Tambah Produk</h1>
            </div>

            <div class="card">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Nama Produk -->
                    <div>
                        <label class="label">Nama Produk *</label>
                        <input v-model="form.name" type="text" class="input" :class="form.errors.name && 'border-red-500'" />
                        <p v-if="form.errors.name" class="text-red-600 text-sm mt-1">{{ form.errors.name }}</p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="label">Deskripsi</label>
                        <textarea v-model="form.description" rows="4" class="input" />
                    </div>

                    <!-- Harga & Stok -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Harga (Rp) *</label>
                            <input v-model.number="form.price" type="number" min="0" class="input" :class="form.errors.price && 'border-red-500'" />
                            <p v-if="form.errors.price" class="text-red-600 text-sm mt-1">{{ form.errors.price }}</p>
                        </div>
                        <div>
                            <label class="label">Stok *</label>
                            <input v-model.number="form.stock" type="number" min="0" class="input" />
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="label">Status Produk</label>
                        <select v-model="form.status" class="input">
                            <option value="draft">Draft (belum tampil)</option>
                            <option value="active">Aktif (tampil di katalog)</option>
                            <option value="inactive">Nonaktif</option>
                        </select>
                    </div>

                    <!-- Upload Foto -->
                    <div>
                        <label class="label">Foto Produk *</label>
                        <div class="flex items-start gap-4">
                            <div v-if="previewUrl" class="w-32 h-32 rounded-xl overflow-hidden border">
                                <img :src="previewUrl" class="w-full h-full object-cover" />
                            </div>
                            <div>
                                <input type="file" accept="image/*" @change="onFileChange" class="block text-sm text-gray-500" />
                                <p class="text-xs text-gray-400 mt-1">JPG, PNG, WebP. Maks 2MB.</p>
                                <p v-if="form.errors.image" class="text-red-600 text-sm mt-1">{{ form.errors.image }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Multi-select Kategori -->
                    <div>
                        <label class="label">Kategori</label>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                            <label v-for="cat in categories" :key="cat.id" 
                                class="flex items-center gap-2 p-2 rounded-lg border cursor-pointer hover:bg-orange-50 transition-colors"
                                :class="form.category_ids.includes(cat.id) && 'border-orange-500 bg-orange-50'">
                                <input type="checkbox" :value="cat.id" v-model="form.category_ids" class="text-orange-600" />
                                <span>{{ cat.icon }} {{ cat.name }}</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tombol -->
                    <div class="flex gap-3">
                        <button type="submit" :disabled="form.processing" class="btn-primary">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Produk' }}
                        </button>
                        <Link :href="route('seller.products.index')" class="btn-secondary">
                            Batal
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>