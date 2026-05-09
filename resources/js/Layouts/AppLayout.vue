<script setup>
import { computed } from 'vue';
import { Link, usePage, router } from '@inertiajs/vue3';

const page  = usePage();
const auth  = computed(() => page.props.auth?.user);
const flash = computed(() => page.props.flash);
</script>

<template>
    <div class="min-h-screen bg-gray-50">
        <!-- NAVBAR -->
        <nav class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16 items-center">
                    <!-- Logo -->
                    <Link href="/" class="flex items-center space-x-2">
                        <span class="text-2xl">🛍</span>
                        <span class="font-bold text-xl text-blue-600">Toko Online</span>
                    </Link>

                    <!-- Navigasi -->
                    <div class="hidden md:flex items-center space-x-6">
                        <Link href="/products" class="text-gray-600 hover:text-blue-600 font-medium">
                            Produk
                        </Link>

                        <!-- Sudah Login -->
                        <template v-if="auth">
                            <!-- Buyer -->
                            <template v-if="auth.role === 'buyer'">
                                <Link href="/wishlist" class="text-gray-600 hover:text-blue-600">
                                    ❤ Wishlist
                                </Link>
                                <Link href="/cart" class="text-gray-600 hover:text-blue-600">
                                    🛒 Keranjang
                                </Link>
                                <Link href="/orders" class="text-gray-600 hover:text-blue-600">
                                    Pesanan
                                </Link>
                            </template>

                            <!-- Seller -->
                            <template v-if="auth.role === 'seller'">
                                <Link href="/seller/products" class="text-gray-600 hover:text-blue-600">
                                    📦 Produk Saya
                                </Link>
                                <Link href="/seller/orders" class="text-gray-600 hover:text-blue-600">
                                    📬 Pesanan Masuk
                                </Link>
                            </template>

                            <!-- Tombol Dashboard -->
                            <Link
                                :href="auth.role === 'admin'  ? '/admin/dashboard'
                                     : auth.role === 'seller' ? '/seller/dashboard'
                                     : '/dashboard'"
                                class="btn-primary text-sm">
                                Dashboard
                            </Link>

                            <!-- Nama + Logout -->
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-600">{{ auth.name }}</span>
                                <Link href="/logout" method="post" as="button"
                                    class="text-sm text-red-500 hover:text-red-700">
                                    Keluar
                                </Link>
                            </div>
                        </template>

                        <!-- Belum Login -->
                        <template v-else>
                            <Link href="/login" class="text-gray-600 hover:text-blue-600 font-medium">
                                Masuk
                            </Link>
                            <Link href="/register" class="btn-primary">
                                Daftar
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <!-- FLASH MESSAGES -->
        <div v-if="flash?.success"
            class="bg-green-50 border-l-4 border-green-500 p-4 max-w-7xl mx-auto mt-4 rounded">
            <p class="text-green-700 text-sm font-medium">✅ {{ flash.success }}</p>
        </div>
        <div v-if="flash?.error"
            class="bg-red-50 border-l-4 border-red-500 p-4 max-w-7xl mx-auto mt-4 rounded">
            <p class="text-red-700 text-sm font-medium">❌ {{ flash.error }}</p>
        </div>

        <!-- KONTEN HALAMAN -->
        <main>
            <slot />
        </main>

        <!-- FOOTER -->
        <footer class="bg-white border-t border-gray-200 mt-16">
            <div class="max-w-7xl mx-auto px-4 py-8 text-center text-gray-500 text-sm">
                © {{ new Date().getFullYear() }} Toko Online.
                Dibuat dengan Laravel 11 + Vue 3 + Inertia.js
            </div>
        </footer>
    </div>
</template>