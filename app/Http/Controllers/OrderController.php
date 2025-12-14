<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\ChatMessages;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    // 1. TAMPILKAN FORMULIR BOOKING (DIPERBAIKI DENGAN TECHNICIAN ID OPTIONAL)
    public function create($technician_id = null)
    {
        $categories = [
            'Cooling & Air Treatment',
            'Entertainment Appliances',
            'Laundry Appliances',
            'Kitchen Appliances',
            'Computing & Home Office',
            'Water Pumps & Heaters'
        ];

        $technician = null;

        if ($technician_id) {
            $technician = User::where('role', 'technician')
                ->where('id', $technician_id)
                ->where('is_verified', true)
                ->first();

            if (!$technician) {
                return redirect()->route('booking.create')->with('error', 'Teknisi tidak valid atau belum diverifikasi. Silakan pilih kategori atau teknisi lain.');
            }
        }

        return view('customer.booking_form', compact('categories', 'technician'));
    }

    // 2. SIMPAN DATA PESANAN KE DATABASE
    public function store(Request $request)
    {
        $request->validate([
            'appliance_name' => 'required',
            'category' => 'required',
            'description' => 'required',
            'technician_id' => 'nullable|exists:users,id',
        ]);

        $status = 'pending';
        $technicianId = $request->technician_id;

        Order::create([
            'customer_id' => Auth::id(),
            'technician_id' => $technicianId,
            'appliance_name' => $request->appliance_name,
            'category' => $request->category,
            'description' => $request->description,
            'status' => $status,
            'total_price' => 0
        ]);

        return redirect()->route('dashboard')->with('success', 'Pesanan berhasil dibuat! Teknisi akan segera merespon.');
    }

    // 3. UPDATE STATUS (Dipakai Teknisi untuk Accept & Timeline Next/Done)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $currentStatus = $order->status;
        $userId = Auth::id();

        // Timeline Lengkap
        $timeline = [
            'pending',
            'accepted',
            'consultation',
            'generating_quote',
            'quote_sent',
            'quote_approved',
            'paid',
            'en_route',
            'service_in_progress',
            'service_complete'
        ];


        // 1. Jika Teknisi klik "Accept"
        if ($request->action == 'accept' && $currentStatus == 'pending') {
            if ($order->technician_id === $userId || $order->technician_id === null) {
                $order->update([
                    'status' => 'accepted',
                    'technician_id' => $userId
                ]);
                return redirect()->route('dashboard')->with('success', 'Job berhasil diterima! Sekarang Anda dapat melihat detailnya.');
            } else {
                return redirect()->back()->with('error', 'Aksi tidak valid. Job ini sudah dialokasikan ke teknisi lain.');
            }
        }

        // 2. Jika Teknisi klik "Next" (Timeline Progress)
        if ($request->action == 'next' && $order->technician_id === $userId) {

            $currentIndex = array_search($currentStatus, $timeline);

            if ($currentIndex !== false) {
                $nextStatusIndex = $currentIndex + 1;

                // Cek apakah status selanjutnya adalah PAID, APPROVED, atau SENT (yang butuh customer action)
                if (in_array($timeline[$nextStatusIndex] ?? null, ['quote_sent', 'quote_approved', 'paid'])) {
                    return redirect()->route('order.show', $order->id)->with('error', 'Langkah ini menunggu persetujuan/pembayaran Customer.');
                }

                // Cek jika status saat ini 'paid', next step harus 'en_route'
                if ($currentStatus == 'paid' && $timeline[$nextStatusIndex] == 'en_route') {
                    $nextStatus = $timeline[$nextStatusIndex];
                    $order->update(['status' => $nextStatus]);
                    return redirect()->route('order.show', $order->id)->with('success', 'Status berhasil diperbarui ke: ' . str_replace('_', ' ', strtoupper($nextStatus)));
                }

                // Lanjut ke status berikutnya yang VALID
                if (isset($timeline[$nextStatusIndex])) {
                    $nextStatus = $timeline[$nextStatusIndex];
                    $order->update(['status' => $nextStatus]);
                    return redirect()->route('order.show', $order->id)->with('success', 'Status berhasil diperbarui ke: ' . str_replace('_', ' ', strtoupper($nextStatus)));
                }
            }
        }

        // 3. Jika Teknisi klik "Done" (Mark Service FINAL)
        if ($request->action == 'done' && $currentStatus == 'service_complete' && $order->technician_id === $userId) {
            // ... (Logika Penghapusan Chat yang sudah ada) ...
            try {
                $deletedCount = ChatMessages::where('order_id', $order->id)->delete();
                $path = 'chat_files/' . $order->id;
                $fileMessage = '';

                if (Storage::disk('public')->exists($path)) {
                    Storage::disk('public')->deleteDirectory($path);
                    $fileMessage = 'dan file terkait telah berhasil dihapus.';
                }

                $order->update(['status' => 'closed']);

                return redirect()->route('dashboard')->with('success', "Service berhasil difinalisasi. {$deletedCount} pesan chat {$fileMessage}");
            } catch (\Exception $e) {
                return redirect()->route('order.show', $order->id)->with('error', 'Gagal memfinalisasi service. Error saat membersihkan data: ' . $e->getMessage());
            }
        }


        return redirect()->back()->with('error', 'Aksi tidak valid atau Anda bukan teknisi yang ditugaskan.');
    }

    // 4. TAMPILKAN DETAIL (TIMELINE & MAP)
    public function show($id)
    {
        $order = Order::with(['customer', 'technician', 'items'])->findOrFail($id);

        return view('order.detail', compact('order'));
    }
    
    // =======================================================
    // LOGIKA QUOTE & PEMBAYARAN
    // =======================================================

    /**
     * TEKNISI: Menyimpan Quote (Items) dan memperbarui total harga order. (Mengembalikan JSON)
     */
    public function saveQuote(Request $request, $orderId)
    {
        $order = Order::where('id', $orderId)
            ->where('technician_id', Auth::id())
            ->firstOrFail();

        $request->validate([
            'total_price' => 'required|numeric|min:0',
            'service_fee' => 'required|numeric',
            'platform_fee_amount' => 'required|numeric',
            'items' => 'nullable|array',
            'items.*.name' => 'required_with:items|string',
            'items.*.qty' => 'required_with:items|integer|min:1',
            'items.*.price' => 'required_with:items|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // 1. Hapus item lama (jika ada)
            $order->items()->delete();

            // 2. Simpan Service Fee
            OrderItem::create([
                'order_id' => $orderId,
                'item_name' => 'Service Fee (Diagnosis, Repair, Cleaning)',
                'quantity' => 1,
                'unit_price' => $request->service_fee,
                'item_type' => 'service',
            ]);

            // 3. Simpan Platform Fee
            OrderItem::create([
                'order_id' => $orderId,
                'item_name' => 'Platform Fee',
                'quantity' => 1,
                'unit_price' => $request->platform_fee_amount,
                'item_type' => 'platform',
            ]);

            // 4. Simpan Parts/Materials
            if ($request->items) {
                foreach ($request->items as $item) {
                    OrderItem::create([
                        'order_id' => $orderId,
                        'item_name' => $item['name'],
                        'quantity' => $item['qty'],
                        'unit_price' => $item['price'],
                        'item_type' => 'part',
                    ]);
                }
            }

            // 5. Update Total Price dan Status ke Quote Sent
            $order->update([
                'total_price' => $request->total_price,
                'status' => 'quote_sent'
            ]);

            DB::commit();

            // Mengembalikan respons JSON karena frontend menggunakan AJAX untuk submit quote teknisi
            return response()->json(['success' => true, 'message' => 'Quote berhasil disimpan dan dikirim ke Customer.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'error' => 'Gagal menyimpan Quote: ' . $e->getMessage()], 500);
        }
    }

    /**
     * CUSTOMER: Menyetujui Quote (Mengubah status ke 'quote_approved'). (NON-AJAX / Redirect)
     */
    public function approveQuote($orderId)
    {
        // Otorisasi dan validasi status
        $order = \App\Models\Order::where('id', $orderId)
            ->where('customer_id', Auth::id())
            ->where('status', 'quote_sent')
            ->firstOrFail();

        $order->update(['status' => 'quote_approved']);

        // Mengembalikan REDIRECT, BUKAN JSON
        return redirect()->route('order.show', $orderId)->with('success', 'Quote berhasil disetujui! Silakan lanjutkan ke pembayaran.');
    }

    /**
     * CUSTOMER: Menandai sebagai Sudah Dibayar (Mengubah status ke 'paid'). (NON-AJAX / Redirect)
     */
    public function markAsPaid($orderId)
    {
        // Otorisasi dan validasi status
        $order = \App\Models\Order::where('id', $orderId)
            ->where('customer_id', Auth::id())
            ->where('status', 'quote_approved')
            ->firstOrFail();

        $order->update(['status' => 'paid']);

        // Mengembalikan REDIRECT, BUKAN JSON
        return redirect()->route('order.show', $orderId)->with('success', 'Pembayaran terkonfirmasi! Teknisi dapat segera memulai.');
    }

    /**
     * CUSTOMER: Mengunduh Invoice (TXT).
     */
    public function downloadInvoice($orderId)
    {
        $order = Order::with(['customer', 'technician', 'items'])
            ->where('id', $orderId)
            ->where(function ($query) {
                $query->where('customer_id', Auth::id())
                    ->orWhere('technician_id', Auth::id());
            })
            ->firstOrFail();

        if ($order->total_price <= 0 || $order->items->isEmpty()) {
            return redirect()->back()->with('error', 'Invoice tidak dapat diunduh karena Quote belum final.');
        }

        // Generate konten TXT
        $content = "===== INVOICE RUMAH BERES =====\n";
        $content .= "Order ID: SV-" . str_pad($order->id, 5, '0', STR_PAD_LEFT) . "\n";
        $content .= "Tanggal: " . $order->created_at->format('d M Y H:i') . " WIB\n";
        $content .= "Customer: " . $order->customer->name . "\n";
        $content .= "Technician: " . $order->technician->name . "\n";
        $content .= "Status Pembayaran: " . strtoupper($order->status) . "\n";
        $content .= "===============================\n\n";

        // Items (Parts & Materials)
        $content .= "PARTS & MATERIALS:\n";
        foreach ($order->items->where('item_type', 'part') as $item) {
            $lineTotal = $item->quantity * $item->unit_price;
            $content .= sprintf(
                " - %s x %d @ Rp %s = Rp %s\n",
                $item->item_name,
                $item->quantity,
                number_format($item->unit_price, 0, ',', '.'),
                number_format($lineTotal, 0, ',', '.')
            );
        }
        $content .= "\n";

        // Fees
        $serviceFee = $order->items->where('item_type', 'service')->first();
        $platformFee = $order->items->where('item_type', 'platform')->first();

        $content .= "SERVICE & FEE:\n";
        $content .= sprintf(" - Service Fee: Rp %s\n", number_format($serviceFee->unit_price ?? 0, 0, ',', '.'));
        $content .= sprintf(" - Platform Fee: Rp %s\n", number_format($platformFee->unit_price ?? 0, 0, ',', '.'));

        $content .= "\n===============================\n";
        $content .= sprintf("TOTAL TAGIHAN: Rp %s\n", number_format($order->total_price, 0, ',', '.'));
        $content .= "===============================\n";

        $filename = "Invoice-Order-{$order->id}-" . time() . ".txt";

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $filename, [
            'Content-Type' => 'text/plain',
        ]);
    }
}
