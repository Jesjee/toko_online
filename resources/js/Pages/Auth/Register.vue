<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';

const form = useForm({
    name:                  '',
    email:                 '',
    role:                  'buyer',
    password:              '',
    password_confirmation: '',
});

const submit = () => {
    form.post('/register', {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100
        flex items-center justify-center p-4">
        <Head title="Daftar" />
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <span class="text-5xl">🛍️</span>
                <h1 class="text-2xl font-bold text-gray-900 mt-3">Buat Akun Baru</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-5">

                <!-- Nama -->
                <div>
                    <label class="label">Nama Lengkap</label>
                    <input v-model="form.name" type="text" class="input"
                        :class="form.errors.name && 'border-red-500'" />
                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">
                        {{ form.errors.name }}
                    </p>
                </div>

                <!-- Email -->
                <div>
                    <label class="label">Email</label>
                    <input v-model="form.email" type="email" class="input"
                        :class="form.errors.email && 'border-red-500'" />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                        {{ form.errors.email }}
                    </p>
                </div>

                <!-- Pilihan Role -->
                <div>
                    <label class="label">Daftar sebagai</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label
                            :class="[
                                'flex items-center justify-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all',
                                form.role === 'buyer'
                                    ? 'border-blue-500 bg-blue-50'
                                    : 'border-gray-200 hover:border-gray-300'
                            ]">
                            <input type="radio" v-model="form.role"
                                value="buyer" class="sr-only" />
                            <span>🛍️</span>
                            <span class="font-medium text-sm">Pembeli</span>
                        </label>
                        <label
                            :class="[
                                'flex items-center justify-center gap-2 p-3 rounded-xl border-2 cursor-pointer transition-all',
                                form.role === 'seller'
                                    ? 'border-blue-500 bg-blue-50'
                                    : 'border-gray-200 hover:border-gray-300'
                            ]">
                            <input type="radio" v-model="form.role"
                                value="seller" class="sr-only" />
                            <span>🏪</span>
                            <span class="font-medium text-sm">Penjual</span>
                        </label>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label class="label">Password</label>
                    <input v-model="form.password" type="password" class="input" />
                    <p v-if="form.errors.password" class="mt-1 text-sm text-red-600">
                        {{ form.errors.password }}
                    </p>
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label class="label">Konfirmasi Password</label>
                    <input v-model="form.password_confirmation"
                        type="password" class="input" />
                </div>

                <!-- Tombol Daftar -->
                <button type="submit" :disabled="form.processing"
                    class="w-full btn-primary py-3 text-base">
                    {{ form.processing ? 'Mendaftar...' : 'Daftar' }}
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Sudah punya akun?
                <Link href="/login" class="text-blue-600 font-medium hover:underline">
                    Masuk di sini
                </Link>
            </p>
        </div>
    </div>
</template>