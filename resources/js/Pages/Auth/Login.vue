<script setup>
import { useForm, Link, Head } from '@inertiajs/vue3';

defineProps({ status: String });

const form = useForm({
    email:    '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-blue-50 to-blue-100
        flex items-center justify-center p-4">
        <Head title="Masuk" />
        <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
            <div class="text-center mb-8">
                <span class="text-5xl">🛍️</span>
                <h1 class="text-2xl font-bold text-gray-900 mt-3">
                    Masuk ke Toko Online
                </h1>
            </div>

            <div v-if="status"
                class="mb-4 text-sm font-medium text-green-600 bg-green-50 rounded-lg p-3">
                {{ status }}
            </div>

            <form @submit.prevent="submit" class="space-y-5">
                <div>
                    <label class="label">Email</label>
                    <input v-model="form.email" type="email" class="input"
                        :class="form.errors.email && 'border-red-500'"
                        autocomplete="email" />
                    <p v-if="form.errors.email" class="mt-1 text-sm text-red-600">
                        {{ form.errors.email }}
                    </p>
                </div>
                <div>
                    <label class="label">Password</label>
                    <input v-model="form.password" type="password" class="input"
                        autocomplete="current-password" />
                </div>
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                    <input v-model="form.remember" type="checkbox"
                        class="rounded text-blue-600" />
                    Ingat saya
                </label>
                <button type="submit" :disabled="form.processing"
                    class="w-full btn-primary py-3 text-base">
                    {{ form.processing ? 'Masuk...' : 'Masuk' }}
                </button>
            </form>

            <p class="text-center text-sm text-gray-500 mt-6">
                Belum punya akun?
                <Link href="/register"
                    class="text-blue-600 font-medium hover:underline">
                    Daftar sekarang
                </Link>
            </p>
        </div>
    </div>
</template>