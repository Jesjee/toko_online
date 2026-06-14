<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({ order: Object });

const form = useForm({ payment_proof: null });
const previewUrl = ref(null);

const rupiah = (n) => new Intl.NumberFormat('id-ID', {
    style: 'currency', currency: 'IDR', maximumFractionDigits: 0
}).format(n);

const onFileChange = (e) => {
    form.payment_proof = e.target.files[0];
    previewUrl.value = URL.createObjectURL(form.payment_proof);
};

const submit = () => form.post(
    route('orders.payment.upload', props.order.id),
    { forceFormData: true }
);
</script>

<template>
    <AppLayout>
        <Head title="Upload Bukti Pembayaran" />
        <div class="max-w-2xl mx-auto px-4 py-8">

            <Link :href="route('orders.show', order.id)"
                class="text-gray-400 hover:text-gray-600 text-sm">
                ← Kembali ke Detail Pesanan
            </Link>

            <h1 class="text-2xl font-bold mt-2 mb-6">💳 Upload Bukti Pembayaran</h1>

            <!-- Info Order -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6">
                <p class="text-sm text-gray-500">Nomor Pesanan</p>
                <p class="font-mono font-bold">{{ order.order_number }}</p>
                <p class="text-2xl font-bold text-rose-600 mt-2">
                    {{ rupiah(order.total_amount) }}
                </p>
                <p class="text-xs text-gray-500 mt-1">Transfer tepat sesuai nominal di atas</p>
            </div>

            <!-- Form Upload -->
            <div class="bg-white border rounded-xl p-6 shadow-sm">
                <h2 class="font-bold mb-4">Foto Bukti Transfer</h2>

                <!-- Preview -->
                <div v-if="previewUrl" class="mb-4">
                    <img :src="previewUrl" class="w-full max-w-xs rounded-xl border shadow-sm" />
                    <p class="text-xs text-gray-400 mt-1">Preview foto yang akan dikirim</p>
                </div>

                <!-- Input file -->
                <div class="border-2 border-dashed border-gray-300 rounded-xl p-6
                            text-center hover:border-rose-400 transition-colors">
                    <p class="text-gray-400 mb-3">📷 Pilih atau seret foto bukti transfer</p>
                    <input type="file" accept="image/*" @change="onFileChange"
                        class="block w-full text-sm text-gray-500
                               file:mr-4 file:py-2 file:px-4 file:rounded-lg
                               file:border-0 file:bg-rose-50 file:text-rose-700
                               hover:file:bg-rose-100" />
                    <p class="text-xs text-gray-400 mt-2">JPG, PNG, WebP. Maks 2MB.</p>
                </div>

                <p v-if="form.errors.payment_proof" class="text-red-600 text-sm mt-2">
                    {{ form.errors.payment_proof }}
                </p>

                <!-- Tombol Submit -->
                <button @click="submit"
                    :disabled="!form.payment_proof || form.processing"
                    class="mt-6 w-full bg-rose-600 text-white py-3 rounded-xl font-bold
                           hover:bg-rose-700 disabled:opacity-50 disabled:cursor-not-allowed
                           transition-colors">
                    {{ form.processing ? 'Mengirim...' : '✅ Kirim Bukti Pembayaran' }}
                </button>
            </div>

        </div>
    </AppLayout>
</template>