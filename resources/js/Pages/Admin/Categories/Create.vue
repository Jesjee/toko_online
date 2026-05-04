<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link, Head } from '@inertiajs/vue3';

const form = useForm({
    name:        '',
    description: '',
    icon:        '',
    is_active:   true,
    sort_order:  0,
});

const submit = () => form.post(route('admin.categories.store'));
</script>

<template>
    <AppLayout>
        <Head title="Tambah Kategori" />
        <div class="max-w-2xl mx-auto px-4 py-8">

            <div class="flex items-center gap-3 mb-6">
                <Link :href="route('admin.categories.index')" class="text-gray-400 hover:text-gray-600">
                    ← Kembali
                </Link>
                <h1 class="text-2xl font-bold">Tambah Kategori</h1>
            </div>

            <div class="card">
                <form @submit.prevent="submit" class="space-y-5">

                    <!-- Nama -->
                    <div>
                        <label class="label">Nama Kategori <span class="text-red-500">*</span></label>
                        <input v-model="form.name" type="text" class="input"
                            :class="form.errors.name && 'border-red-500'"
                            placeholder="contoh: Elektronik" />
                        <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.name }}
                        </p>
                        <p class="mt-1 text-xs text-gray-400">
                            Slug akan dibuat otomatis dari nama.
                        </p>
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label class="label">Deskripsi</label>
                        <textarea v-model="form.description" rows="3" class="input"
                            placeholder="Opsional — keterangan singkat kategori" />
                        <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">
                            {{ form.errors.description }}
                        </p>
                    </div>

                    <!-- Icon & Urutan -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="label">Icon (emoji)</label>
                            <input v-model="form.icon" type="text" class="input"
                                placeholder="🎁" maxlength="10" />
                        </div>
                        <div>
                            <label class="label">Urutan Tampil</label>
                            <input v-model.number="form.sort_order" type="number"
                                class="input" min="0" />
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center gap-3">
                        <input v-model="form.is_active" type="checkbox" id="is_active"
                            class="w-4 h-4 text-teal-600" />
                        <label for="is_active" class="text-sm font-medium text-gray-700">
                            Kategori aktif (tampil di halaman publik)
                        </label>
                    </div>

                    <!-- Tombol -->
                    <div class="flex gap-3 pt-2">
                        <button type="submit" :disabled="form.processing" class="btn-primary">
                            {{ form.processing ? 'Menyimpan...' : 'Simpan Kategori' }}
                        </button>
                        <Link :href="route('admin.categories.index')" class="btn-secondary">
                            Batal
                        </Link>
                    </div>

                </form>
            </div>

        </div>
    </AppLayout>
</template>