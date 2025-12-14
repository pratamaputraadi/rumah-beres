<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users'); // Pemesan
            $table->foreignId('technician_id')->nullable()->constrained('users'); // Teknisi (bisa kosong dulu)

            $table->string('appliance_name'); // Nama barang (AC Samsung)
            $table->text('description'); // Keluhan

            // Status: pending, consultation, en_route, in_progress, completed
            $table->string('status')->default('pending');

            // Harga (Bisa kosong kalau belum dicek)
            $table->decimal('total_price', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
