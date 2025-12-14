<?php

// app/Models/ChatMessages.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessages extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'sender_id',
        'message',
        'file_url',
        'media_type',
    ];

    /**
     * Relasi ke Pengirim (Sender). KRUSIAL untuk getHistory.
     */
    public function sender()
    {
        // Menghubungkan sender_id di chat_messages ke id di users
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Relasi ke Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
