<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Mendefinisikan kolom yang boleh diisi melalui mass assignment
    protected $fillable = [
        'order_id',
        'item_name',
        'quantity',
        'unit_price',
        'item_type', // 'service', 'part', 'platform'
    ];

    /**
     * Relasi ke Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
