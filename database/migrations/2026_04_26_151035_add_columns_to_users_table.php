<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Kolom role - hanya menerima tiga nilai ini
            // Default 'buyer' karena sebagian besar pendaftar adalah pembeli
            $table->enum('role', ['admin', 'seller', 'buyer'])
                ->default('buyer')
                ->after('password');

            // Kolom profil tambahan - semua nullable
            $table->string('phone', 20)->nullable()->after('role');
            $table->text('address')->nullable()->after('phone');
            $table->string('avatar')->nullable()->after('address');

            // Kolom untuk aktif/nonaktif user - dipakai admin di P12
            $table->boolean('is_active')->default(true)->after('avatar');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'address', 'avatar', 'is_active']);
        });
    }
};