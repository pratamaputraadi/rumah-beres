<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // Relasi ke Teknisi
    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    /**
     * Relasi ke Item/Quote (Krusial untuk fitur Invoice).
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
