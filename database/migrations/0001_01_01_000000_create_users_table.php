<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // --- KOLOM TAMBAHAN KHUSUS RUMAH BERES ---
            $table->string('role')->default('customer'); // admin, technician, customer
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('avatar')->nullable(); // Foto profil

            // Khusus Teknisi
            $table->string('specialization')->nullable(); // Spesialis AC/Kulkas
            $table->boolean('is_verified')->default(false); // Status verifikasi admin

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
