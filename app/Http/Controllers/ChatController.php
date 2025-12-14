<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChatMessages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ChatController extends Controller
{
    /**
     * Menyimpan pesan teks atau file ke database (API POST /chat/send).
     */
    public function sendMessage(Request $request)
    {
        try {
            // 1. Validasi Input
            $request->validate([
                'order_id' => 'required|exists:orders,id',
                'message' => 'nullable|string',
                'file' => 'nullable|file|mimes:jpeg,png,jpg,pdf|max:5120', // Maks 5MB
            ]);

            $orderId = $request->order_id;
            $fileUrl = null;
            $mediaType = 'text';

            if (!$request->message && !$request->hasFile('file')) {
                throw ValidationException::withMessages(['message' => 'Pesan atau file tidak boleh kosong.']);
            }

            // 2. Proses Upload File (Jika ada)
            if ($request->hasFile('file')) {
                $file = $request->file('file');

                // Mendapatkan tipe media untuk tampilan (misal: image/jpeg)
                $mediaType = $file->getClientMimeType();

                // Menyimpan file di folder /storage/app/public/chat_files/{order_id}
                $path = 'chat_files/' . $orderId;
                $fileUrl = $file->store($path, 'public');
            }

            // 3. Simpan Pesan ke Database
            $message = ChatMessages::create([
                'order_id' => $orderId,
                'sender_id' => Auth::id(),
                'message' => $request->message,
                'file_url' => $fileUrl,
                'media_type' => $mediaType,
            ]);

            // Load data sender (nama dan role) untuk dikirim kembali ke frontend
            $message->load('sender:id,name,role');

            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dikirim.',
                'data' => $message
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'validation_errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mengambil riwayat pesan untuk order tertentu (API GET /chat/history/{orderId}).
     */
    public function getHistory($orderId)
    {
        $userId = Auth::id();

        // Pastikan order ini milik user yang sedang login (keamanan dasar)
        $order = \App\Models\Order::where('id', $orderId)
            ->where(function ($query) use ($userId) {
                $query->where('customer_id', $userId)
                    ->orWhere('technician_id', $userId);
            })->first();

        // =========================================================================
        // !!! TEMPORARY DEBUGGING START (Untuk mengecek otorisasi dan order ID) !!!
        // =========================================================================

        if ($order) {
            dd([
                'User ID Yang Login' => $userId,
                'Order ID Dari URL' => $orderId,
                'Order Ditemukan' => true,
                'Order Customer ID' => $order->customer_id,
                'Order Technician ID' => $order->technician_id,
                'Hasil Otorisasi' => 'Success'
            ]);
        } else {
            dd([
                'User ID Yang Login' => $userId,
                'Order ID Dari URL' => $orderId,
                'Order Ditemukan' => false,
                'Hasil Otorisasi' => 'Failed'
            ]);
        }

        // =========================================================================
        // !!! TEMPORARY DEBUGGING END !!! HAPUS KODE dd([...]) SETELAH PENGUJIAN
        // =========================================================================

        if (!$order) {
            return response()->json(['success' => false, 'error' => 'Order not found or unauthorized'], 403);
        }

        // Ambil pesan dan informasi pengirim
        $messages = ChatMessages::where('order_id', $orderId)
            ->with('sender:id,name,role')
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'messages' => $messages
        ]);
    }
}
