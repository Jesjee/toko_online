<script setup>
// defineProps menerima data yang dikirim dari HomeController
// nama props harus sama persis dengan key di array Inertia::render()
defineProps({
    totalProduk:  Number,
    totalPenjual: Number,
});
</script>
 
<template>
    <div class="min-h-screen bg-gray-50">
 
        <!-- Hero Section -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-800 text-white">
            <div class="max-w-7xl mx-auto px-4 py-20 text-center">
                <h1 class="text-5xl font-bold mb-4">
                    Selamat Datang di Toko Online
                </h1>
                <p class="text-blue-100 text-lg">
                    {{ totalProduk }} produk dari {{ totalPenjual }} penjual.
                </p>
            </div>
        </div>
 
        <!-- Kartu Info -->
        <div class="max-w-7xl mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
 
                <div class="card text-center">
                    <p class="text-4xl mb-3">📦</p>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ totalProduk }}
                    </p>
                    <p class="text-gray-500 mt-1">Produk Tersedia</p>
                </div>
 
                <div class="card text-center">
                    <p class="text-4xl mb-3">🏪</p>
                    <p class="text-3xl font-bold text-blue-600">
                        {{ totalPenjual }}
                    </p>
                    <p class="text-gray-500 mt-1">Penjual Aktif</p>
                </div>
 
            </div>
        </div>
 
    </div>
</template>