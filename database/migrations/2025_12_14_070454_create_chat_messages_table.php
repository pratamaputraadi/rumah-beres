<?php

// database/migrations/..._create_chat_messages_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();

            // Foreign Key ke Order yang sedang dikerjakan
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Foreign Key ke User yang mengirim (bisa Customer atau Technician)
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');

            // Konten Pesan (Teks)
            $table->text('message')->nullable();

            // URL File/Foto (Jika pesan berisi gambar, video, atau dokumen)
            $table->string('file_url')->nullable();

            // Tipe media (misalnya: 'text', 'image', 'video')
            $table->string('media_type', 20)->default('text');

            $table->timestamps();

            // Tambahkan index agar pencarian per order cepat
            $table->index(['order_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chat_messages');
    }
};
