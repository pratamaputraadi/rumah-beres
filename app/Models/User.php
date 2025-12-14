<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * PENTING: Semua nama kolom ini HARUS ada di sini agar bisa disimpan controller.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',              // admin, customer, technician
        'phone',
        'address',
        'bio',               // <--- TAMBAHAN BARU (Supaya Bio bisa disimpan)
        'avatar',

        // --- Data Khusus Teknisi (WAJIB ADA DISINI) ---
        'specialization',
        'is_verified',       // Agar kita bisa set jadi 0 (pending)
        'ktp_photo',
        'certificate_photo'
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        // 'is_verified' => 'boolean', // Biarkan dikomentari jika ingin melihat angka 0/1 murni
    ];

    // Relasi
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    public function jobs()
    {
        return $this->hasMany(Order::class, 'technician_id');
    }

    public function sentMessages()
    {
        // Pesan yang dikirim oleh User ini
        return $this->hasMany(ChatMessages::class, 'sender_id');
    }
}
