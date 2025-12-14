<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom ktp_photo dan certificate_photo
            // Kita taruh setelah kolom 'avatar' biar rapi
            $table->string('ktp_photo')->nullable()->after('avatar');
            $table->string('certificate_photo')->nullable()->after('ktp_photo');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['ktp_photo', 'certificate_photo']);
        });
    }
};
