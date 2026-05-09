<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        {{-- Judul halaman diambil dari komponen Vue --}}
        <title inertia>{{ config('app.name', 'Toko Online') }}</title>

        {{-- Dibutuhkan Ziggy agar named route bisa dipakai di Vue --}}
        @routes

        {{-- Memuat file CSS dan JS yang diproses oleh Vite --}}
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Dibutuhkan Inertia untuk meta tags --}}
        @inertiaHead
    </head>
    <body class="font-sans antialiased bg-gray-50">
        {{-- Vue akan dirender di dalam directive ini --}}
        @inertia
    </body>
</html>