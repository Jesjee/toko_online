<script setup>
defineProps({ user: Object });
</script>

<template>
    <div class="min-h-screen bg-gray-50 flex items-center justify-center">
        <div class="card text-center max-w-md">
            <p class="text-4xl mb-4">🛍️</p>
            <h1 class="text-2xl font-bold mb-2">
                Selamat Datang, {{ user?.name }}!
            </h1>
            <p class="text-gray-500">Dashboard Buyer — akan dilengkapi di P10</p>
            <p class="text-xs mt-2 text-blue-600 font-mono">role: buyer ✅</p>
        </div>
    </div>
</template>