<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link, Head } from '@inertiajs/vue3';

const props = defineProps({
    items: Array,
    total: Number,
});

const form = useForm({
    shipping_address: '',
    phone: '',
    notes: '',
    items: props.items.map(i => ({
        product_id: i.product_id,
        qty: i.qty,
    })),
});

const rupiah = (n) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
}).format(n);

const submit = () => form.post(route('checkout.store'));
</script>

<template>
    <AppLayout>
        <Head title="Checkout" />
        <div class="max-w-5xl mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold mb-8">🛒 Konfirmasi Pesanan</h1>

            <div v-if="form.errors.checkout"
                class="bg-red-50 border-l-4 border-red-500 p-4 rounded mb-6">
                <p class="text-red-700 font-medium">❌ {{ form.errors.checkout }}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Kiri: Form Pengiriman -->
                <div class="card">
                    <h2 class="text-lg font-bold mb-4">📍 Informasi Pengiriman</h2>
                    <form @submit.prevent="submit" class="space-y-4">
                        <div>
                            <label class="label">Alamat Pengiriman *</label>
                            <textarea v-model="form.shipping_address" rows="3"
                                class="input"
                                :class="form.errors.shipping_address && 'border-red-500'"
                                placeholder="Jl. Contoh No. 123, Kota, Provinsi" />
                            <p v-if="form.errors.shipping_address" class="text-red-600 text-sm mt-1">
                                {{ form.errors.shipping_address }}
                            </p>
                        </div>
                        <div>
                            <label class="label">Nomor Telepon *</label>
                            <input v-model="form.phone" type="tel" class="input"
                                :class="form.errors.phone && 'border-red-500'"
                                placeholder="08xxxxxxxxxx" />
                            <p v-if="form.errors.phone" class="text-red-600 text-sm mt-1">
                                {{ form.errors.phone }}
                            </p>
                        </div>
                        <div>
                            <label class="label">Catatan (opsional)</label>
                            <textarea v-model="form.notes" rows="2" class="input"
                                placeholder="Catatan untuk seller..." />
                        </div>
                        <button type="submit" :disabled="form.processing"
                            class="w-full btn-primary py-3 text-base">
                            {{ form.processing ? 'Memproses...' : '✅ Pesan Sekarang' }}
                        </button>
                    </form>
                </div>

                <!-- Kanan: Ringkasan Pesanan -->
                <div class="card">
                    <h2 class="text-lg font-bold mb-4">📦 Ringkasan Pesanan</h2>
                    <div class="space-y-3">
                        <div v-for="item in items" :key="item.product_id"
                            class="flex gap-3 items-center py-2 border-b">
                            <img :src="item.product_image ? '/storage/'+item.product_image : '/img/no-image.png'"
                                class="w-14 h-14 object-cover rounded-lg" />
                            <div class="flex-1">
                                <p class="font-medium text-sm">{{ item.product_name }}</p>
                                <p class="text-gray-500 text-xs">
                                    {{ rupiah(item.price) }} × {{ item.qty }}
                                </p>
                            </div>
                            <p class="font-bold text-teal-700">
                                {{ rupiah(item.price * item.qty) }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-4 pt-4 border-t flex justify-between items-center">
                        <span class="font-bold text-lg">Total</span>
                        <span class="font-bold text-xl text-teal-700">{{ rupiah(total) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>