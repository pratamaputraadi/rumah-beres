@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8">

    <div class="md:col-span-2 space-y-6">

        @if(session('success'))
        <div class="bg-green-900/50 border border-green-700 text-green-300 p-3 rounded-lg mb-6 text-sm">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-900/50 border border-red-700 text-red-300 p-3 rounded-lg mb-6 text-sm">
            {{ session('error') }}
        </div>
        @endif

        {{-- [BAGIAN ATAS, ORDER FROM, DAN ORDER DETAILS TETAP SAMA] --}}

        @if(Auth::user()->role == 'technician' || Auth::user()->role == 'admin')
        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
            <h3 class="text-xl font-bold mb-3 text-white">Order From</h3>
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 bg-gray-600 rounded-full flex items-center justify-center text-xl font-bold text-white">
                    {{ substr($order->customer->name, 0, 1) }}
                </div>
                <div>
                    <h4 class="font-bold text-lg text-white">{{ $order->customer->name }}</h4>
                    <p class="text-sm text-gray-400">Email: {{ $order->customer->email ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-400">Phone: {{ $order->customer->phone ?? 'N/A' }}</p>
                </div>
            </div>

            <h4 class="font-bold text-white mt-4 mb-2">Location</h4>
            <p class="text-sm text-gray-300 bg-gray-900 p-3 rounded-lg">
                {{ $order->customer->address ?? 'Alamat tidak tersedia. Harap hubungi Customer.' }}
            </p>
        </div>
        @endif


        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-2xl font-bold">{{ $order->appliance_name }}</h2>
                    <p class="text-gray-400">Category: {{ $order->category }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold 
                    {{ $order->status == 'pending' ? 'bg-yellow-500/20 text-yellow-500' : 'bg-green-500/20 text-green-500' }}">
                    {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                </span>
            </div>

            @if($order->technician)
            <div class="mt-6 flex items-center gap-4 bg-gray-900 p-4 rounded-xl">
                <div class="h-12 w-12 bg-blue-600 rounded-full flex items-center justify-center font-bold">
                    {{ substr($order->technician->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-sm text-gray-400">Technician (Assigned)</p>
                    <p class="font-bold text-lg">{{ $order->technician->name }}</p>
                    <p class="text-xs text-yellow-500">★ 4.8 ({{ $order->technician->specialization }})</p>
                </div>

                @if(Auth::user()->role == 'technician' || Auth::user()->role == 'admin')
                <a href="tel:{{ $order->technician->phone }}" class="ml-auto bg-gray-700 p-2 rounded-lg hover:bg-gray-600">
                    📞
                </a>
                @endif
            </div>
            @else
            <div class="mt-6 p-4 rounded-xl bg-gray-900 border border-yellow-700 text-yellow-400">
                <p class="text-sm font-semibold">Menunggu alokasi/penerimaan Teknisi.</p>
            </div>
            @endif
        </div>

        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
            <h3 class="font-bold mb-6">Service Timeline</h3>

            @php
            $serviceTimeline = [
            'accepted' => ['label' => 'Technician Accepted Job', 'desc' => 'Teknisi telah menerima dan mengalokasikan job ini.'],
            'consultation' => ['label' => 'Online Consultation', 'desc' => 'Teknisi sedang mendiagnosis masalah via online.'],
            'generating_quote' => ['label' => 'Generating Quote', 'desc' => 'Teknisi sedang menghitung estimasi biaya perbaikan.'],
            'quote_sent' => ['label' => 'Quote Sent', 'desc' => 'Penawaran harga telah dikirim. Tekan ikon kertas untuk melihat invoice.'],
            'quote_approved' => ['label' => 'Quote Approved / Payment Pending', 'desc' => 'Quote disetujui. Tekan ikon kertas untuk melanjutkan pembayaran.'],
            'paid' => ['label' => 'Payment Paid', 'desc' => 'Pembayaran berhasil dikonfirmasi.'],
            'en_route' => ['label' => 'Technician En-route', 'desc' => 'Teknisi sedang dalam perjalanan ke lokasi Anda.'],
            'service_in_progress' => ['label' => 'Service In-progress', 'desc' => 'Perbaikan sedang dilakukan di tempat Customer.'],
            'service_complete' => ['label' => 'Service Complete', 'desc' => 'Job selesai. Menunggu konfirmasi & pembayaran Customer.'],
            ];
            $statuses = array_keys($serviceTimeline);
            $currentStatusIndex = array_search($order->status, $statuses);

            $hasNextStep = ($currentStatusIndex !== false && $currentStatusIndex < count($statuses) - 1);
                $isChatActive=$currentStatusIndex>= array_search('accepted', $statuses);

                // URL Google Maps Sederhana
                $mapUrl = 'https://maps.app.goo.gl/jBBTkA3SQ4wS9V7P8';
                @endphp

                <div class="relative border-l-2 border-gray-700 ml-3 space-y-8">

                    {{-- Tampilkan Status 'Pending' (di luar loop service) --}}
                    <div class="ml-6 relative">
                        <div class="absolute -left-[31px] bg-green-500 h-4 w-4 rounded-full border-4 border-gray-800"></div>
                        <h4 class="font-bold">Order Created / Pending</h4>
                        <p class="text-xs text-gray-400">Permintaan servis Anda telah diterima.</p>
                    </div>

                    @foreach($serviceTimeline as $key => $data)
                    @php
                    $stepIndex = array_search($key, $statuses);
                    $isCurrent = $key == $order->status;
                    $isCompleted = $stepIndex < $currentStatusIndex;
                        $dotClass=$isCompleted ? 'bg-green-500' : ($isCurrent ? 'bg-blue-500' : 'bg-gray-600' );
                        $textClass=$isCompleted ? 'text-gray-400' : ($isCurrent ? 'text-white font-bold' : 'text-gray-500' );
                        $icon=$isCompleted ? '✅' : ($isCurrent ? '⚙️' : '⏺️' );

                        $showChatIcon=($key=='consultation' && $isChatActive);
                        @endphp

                        <div class="ml-6 relative">
                        <div class="absolute -left-[31px] h-4 w-4 rounded-full border-4 border-gray-800 {{ $dotClass }} flex items-center justify-center text-xs">
                            {{ $icon }}
                        </div>

                        <div class="flex items-center justify-between">
                            <h4 class="{{ $textClass }} font-bold">{{ $data['label'] }}
                                @if($isCurrent) <span class="bg-blue-900 text-blue-300 text-xs font-medium ml-2 px-2 py-0.5 rounded">CURRENT</span> @endif
                            </h4>

                            {{-- Ikon Chat (Hanya buka modal) --}}
                            @if($showChatIcon)
                            <a href="#chat-modal" class="text-lg text-blue-400 hover:text-blue-300 transition"
                                onclick="document.getElementById('chat-modal').classList.remove('hidden');">
                                💬
                            </a>
                            @endif

                            {{-- Ikon Quote INPUT (Hanya Teknisi, di status generating_quote) --}}
                            @if($key == 'generating_quote' && $order->technician_id == Auth::id())
                            <a href="#quote-modal" class="text-lg text-yellow-400 hover:text-yellow-300 transition"
                                onclick="document.getElementById('quote-modal').classList.remove('hidden'); resetQuoteForm();">
                                💰
                            </a>
                            @endif

                            {{-- Ikon View Invoice (Muncul saat quote_sent atau lebih) --}}
                            @if(in_array($key, ['quote_sent', 'quote_approved']) && in_array($order->status, ['quote_sent', 'quote_approved', 'paid', 'en_route', 'service_in_progress', 'service_complete', 'closed']))
                            <a href="#invoice-modal" class="text-lg text-purple-400 hover:text-purple-300 transition"
                                onclick="document.getElementById('invoice-modal').classList.remove('hidden');">
                                🧾
                            </a>
                            @endif

                            {{-- IKON MAP BARU (TECHNICIAN EN-ROUTE) --}}
                            @if($key == 'en_route' && $isCurrent)
                            <a href="{{ $mapUrl }}" target="_blank" class="text-lg text-red-500 hover:text-red-400 transition">
                                📍
                            </a>
                            @endif

                        </div>

                        <p class="text-xs text-gray-400">{{ $data['desc'] }}</p>
                </div>
                @endforeach
        </div>

        @if(Auth::user()->role == 'technician' && $order->technician_id == Auth::id())
        <form action="{{ route('booking.update', $order->id) }}" method="POST" class="mt-8 flex gap-3">
            @csrf

            {{-- TOMBOL MARK SERVICE FINAL (Hanya di service_complete) --}}
            @if($order->status == 'service_complete')
            <input type="hidden" name="action" value="done">
            <button type="submit" class="flex-1 py-3 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold transition">
                Mark Service FINAL
            </button>

            {{-- TOMBOL AWAITING CUSTOMER (Selama masa negosiasi/pembayaran) --}}
            @elseif(in_array($order->status, ['generating_quote', 'quote_sent', 'quote_approved']))
            <button type="button" disabled class="flex-1 py-3 rounded-xl bg-gray-600 text-gray-400 font-bold cursor-not-allowed">
                Awaiting Customer Action...
            </button>

            {{-- TOMBOL AWAITING ACCEPTANCE (Hanya di pending) --}}
            @elseif($order->status == 'pending')
            <button type="button" disabled class="flex-1 py-3 rounded-xl bg-gray-600 text-gray-400 font-bold cursor-not-allowed">
                Awaiting Acceptance
            </button>

            {{-- TOMBOL NEXT STEP (EN_ROUTE, setelah paid) --}}
            @elseif($order->status == 'paid')
            <input type="hidden" name="action" value="next">
            <button type="submit" class="flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold transition">
                Next Step (EN_ROUTE)
            </button>

            {{-- TOMBOL NEXT STEP UMUM --}}
            @elseif($hasNextStep && $currentStatusIndex !== false)
            <input type="hidden" name="action" value="next">
            <button type="submit" class="flex-1 py-3 rounded-xl bg-blue-600 hover:bg-blue-500 text-white font-bold transition">
                Next Step ({{ strtoupper(str_replace('_', ' ', $statuses[$currentStatusIndex + 1])) }})
            </button>
            @endif

            <a href="{{ route('technician.accepted_jobs') }}" class="py-3 px-6 bg-gray-700 text-center rounded-xl hover:bg-gray-600 font-bold">
                Back to Requests
            </a>
        </form>
        @else
        {{-- Tombol Kembali untuk Customer/Admin --}}
        <a href="{{ route('dashboard') }}" class="block w-full py-3 bg-gray-700 text-center rounded-xl hover:bg-gray-600 font-bold mt-8">
            Back to Dashboard
        </a>
        @endif

    </div>
    <div class="space-y-6">

        <div class="bg-gray-800 p-2 rounded-2xl border border-gray-700 h-64 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gray-700 flex items-center justify-center">
                <span class="text-gray-500 text-sm">[ Map Visualization Here ]</span>
            </div>

            @if($order->technician && $order->status == 'en_route')
            <div class="absolute bottom-4 left-4 right-4 bg-gray-900/90 backdrop-blur p-3 rounded-xl border border-gray-600">
                <p class="text-xs text-gray-400">Technician ETA</p>
                <p class="font-bold text-white">25 Mins</p>
            </div>
            @endif
        </div>

        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700">
            <h3 class="font-bold mb-2 text-sm text-gray-400">Issue Description</h3>
            <p class="text-sm text-white">{{ $order->description }}</p>
        </div>

    </div>
</div>
@endsection

{{--========================================================================================--}}
{{-- MODAL CHAT (Disederhanakan) --}}
{{--========================================================================================--}}
<div id="chat-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg shadow-2xl w-full max-w-lg p-6 border border-gray-700">
        <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
            <h3 class="text-xl font-bold text-white">
                Online Consultation Chat (Simulasi)
                @if($order->technician && $order->customer)
                ({{ Auth::user()->role == 'technician' ? $order->customer->name : $order->technician->name }})
                @endif
            </h3>
            <button class="text-gray-400 hover:text-white" onclick="document.getElementById('chat-modal').classList.add('hidden')">
                ×
            </button>
        </div>

        <div id="chat-messages" class="h-64 overflow-y-auto mb-4 p-2 bg-gray-900 rounded border border-gray-700 space-y-2">
            <p class="text-center text-sm text-gray-500 mt-20" id="loading-indicator">
                Fungsi kirim/terima pesan dinonaktifkan. Ini hanya simulasi box chat.
            </p>
        </div>

        <form id="chat-form" onsubmit="return false;" enctype="multipart/form-data">
            <input type="text" id="message-input" name="message" placeholder="Ketik pesan..." class="flex-1 w-full p-3 bg-gray-700 rounded-lg border border-gray-600 text-white" disabled>
            <button type="submit" id="send-btn" class="w-full py-2 mt-2 rounded-lg bg-gray-600 text-gray-400 font-bold transition cursor-not-allowed" disabled>
                Send (Nonaktif)
            </button>
        </form>
    </div>
</div>


{{--========================================================================================--}}
{{-- MODAL INPUT QUOTE (Hanya untuk Teknisi) --}}
{{--========================================================================================--}}
@if(Auth::user()->role == 'technician' && $order->technician_id == Auth::id())
<div id="quote-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg shadow-2xl w-full max-w-lg p-6 border border-gray-700">
        <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
            <h3 class="text-xl font-bold text-white">Input Service Quote (Order #{{ $order->id }})</h3>
            <button class="text-gray-400 hover:text-white" onclick="document.getElementById('quote-modal').classList.add('hidden'); resetQuoteForm();">
                ×
            </button>
        </div>

        {{-- KRUSIAL: Form Teknisi sekarang menggunakan Form Submission (POST) biasa --}}
        <form id="quote-input-form" action="{{ route('order.save_quote', $order->id) }}" method="POST">
            @csrf

            <h4 class="text-md font-bold text-white mb-3">Parts & Materials</h4>

            <div id="item-container" class="space-y-3 mb-4 max-h-40 overflow-y-auto pr-2">
                {{-- Item list will be appended here by JavaScript --}}
            </div>

            <a href="#" onclick="addItemInput('part'); return false;" class="text-xs text-blue-400 hover:text-blue-300 mb-4 flex items-center font-semibold">
                + Tambah Item/Part
            </a>

            <div class="space-y-3 text-sm">
                {{-- Service Fee (Static Input) --}}
                <div class="flex justify-between items-center bg-gray-700 p-3 rounded-lg">
                    <span>Service Fee (Diagnosis, Repair, Cleaning)</span>
                    <input type="number" name="service_fee" id="service_fee" value="100000" min="0" required
                        class="w-24 bg-gray-900 text-white p-1 rounded text-right focus:outline-none focus:ring-1 focus:ring-blue-500"
                        oninput="calculateTotal()">
                </div>

                {{-- Platform Fee (Static/Hidden Input) --}}
                <div class="flex justify-between items-center bg-gray-700 p-3 rounded-lg">
                    <span>Platform Fee</span>
                    <input type="number" name="platform_fee_amount" id="platform_fee_amount" value="15000" min="0" required
                        class="w-24 bg-gray-900 text-white p-1 rounded text-right focus:outline-none focus:ring-1 focus:ring-blue-500"
                        oninput="calculateTotal()">
                </div>

                <div class="flex justify-between items-center text-lg font-bold border-t border-gray-600 pt-3 mt-3">
                    <span>TOTAL TAGIHAN</span>
                    <span id="subtotal-display" class="text-yellow-400">Rp 115.000</span>
                    <input type="hidden" id="total_price_input" name="total_price" value="115000">
                </div>
                <p id="quote-error" class="text-xs text-red-400"></p>
            </div>

            <button type="submit" class="w-full py-3 mt-6 rounded-xl bg-green-600 hover:bg-green-500 text-white font-bold transition">
                Save & Send Quote to Customer
            </button>
        </form>
    </div>
</div>
@endif


{{--========================================================================================--}}
{{-- MODAL INVOICE (Modal yang muncul saat klik ikon kertas 🧾) --}}
{{--========================================================================================--}}
@if(in_array($order->status, ['quote_sent', 'quote_approved', 'paid', 'en_route', 'service_in_progress', 'service_complete', 'closed']))
<div id="invoice-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg shadow-2xl w-full max-w-lg p-6 border border-gray-700">
        <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
            <h3 class="text-xl font-bold text-white">Payment & Invoice Details</h3>
            <button class="text-gray-400 hover:text-white" onclick="document.getElementById('invoice-modal').classList.add('hidden');">
                ×
            </button>
        </div>

        <div id="invoice-content" class="space-y-4">
            @php
            // Ambil Item berdasarkan tipe
            $serviceFee = $order->items->where('item_type', 'service')->first();
            $platformFee = $order->items->where('item_type', 'platform')->first();
            $parts = $order->items->where('item_type', 'part');

            // Simulasi data VA dan Pay Before
            $vaNumber = '8808 ' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            $payBefore = now()->addHours(24)->format('D, d/m/y, H:i'). ' WIB';

            // Status Pembayaran
            $paymentStatus = match ($order->status) {
            'paid' => ['label' => 'Paid', 'color' => 'bg-green-500 text-black'],
            'quote_approved' => ['label' => 'Payment Pending', 'color' => 'bg-yellow-500 text-black'],
            'quote_sent' => ['label' => 'Awaiting Approval', 'color' => 'bg-yellow-700 text-white'],
            default => ['label' => 'Paid', 'color' => 'bg-green-500 text-black'],
            };
            @endphp

            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-400">Order ID: <span class="text-white">SV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                <a href="{{ route('order.download_invoice', $order->id) }}" class="bg-gray-700 hover:bg-gray-600 text-white text-xs px-3 py-1 rounded-lg">
                    Download Invoice (TXT)
                </a>
            </div>

            <div class="bg-gray-900 p-4 rounded-xl space-y-3">
                <p class="text-sm text-gray-400">Date: <span class="text-white">{{ $order->created_at->format('M d, Y') }}</span></p>
                <p class="text-sm text-gray-400">Technician: <span class="text-white">{{ $order->technician->name ?? 'N/A' }}</span></p>
            </div>

            <h4 class="font-bold text-white mt-4">Invoice</h4>
            <div class="space-y-2 text-sm">

                {{-- Service Fee --}}
                <div class="flex justify-between text-gray-300">
                    <span>Service Fee</span>
                    <span>Rp {{ number_format($serviceFee->unit_price ?? 0, 0, ',', '.') }}</span>
                </div>

                <h5 class="text-gray-400 font-semibold mt-3">Parts & Materials</h5>
                @forelse($parts as $item)
                <div class="flex justify-between text-gray-300 ml-2">
                    <span>{{ $item->quantity }}x {{ $item->item_name }}</span>
                    <span>Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</span>
                </div>
                @empty
                <div class="text-gray-500 ml-2">No Parts Required</div>
                @endforelse

                {{-- Platform Fee --}}
                <div class="flex justify-between text-gray-300 border-t border-gray-700 pt-2">
                    <span>Platform Fee</span>
                    <span>Rp {{ number_format($platformFee->unit_price ?? 0, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between text-lg font-bold border-t border-gray-600 pt-3">
                    <span>Total Tagihan</span>
                    <span class="text-yellow-400">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <h4 class="font-bold text-white pt-4">Complete Your Payment</h4>

            {{-- FORM SUBMISSION UNTUK APPROVE (NON-AJAX) --}}
            @if($order->status == 'quote_sent' && Auth::user()->role == 'customer')
            <form action="{{ route('order.approve', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2 mt-3 rounded-lg bg-green-600 hover:bg-green-500 text-white font-bold transition">
                    Approve Quote & Continue to Payment
                </button>
            </form>
            @endif

            {{-- DETAIL PEMBAYARAN & MARK PAID (NON-AJAX) --}}
            @if($order->status == 'quote_approved')
            <div class="bg-gray-900 p-4 rounded-xl space-y-3">
                <p class="text-sm text-gray-400">Pay Before: <span class="font-bold text-white">{{ $payBefore }}</span></p>
                <p class="text-sm text-gray-400">VA Number: <span class="font-bold text-white">{{ $vaNumber }}</span></p>
                <p class="text-sm text-gray-400">Payment Method: <span class="text-white">BCA, BRI, BTN, Dana, OVO</span></p>

                @if(Auth::user()->role == 'customer')
                <form action="{{ route('order.mark_paid', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2 mt-3 rounded-lg bg-green-600 hover:bg-green-500 text-white font-bold transition">
                        Mark as Paid / Done
                    </button>
                </form>
                @endif
            </div>
            @endif

            {{-- STATUS PEMBAYARAN --}}
            <div class="flex justify-between items-center pt-3 border-t border-gray-700">
                <span class="text-sm text-gray-400">Payment Status:</span>
                <span class="px-3 py-1 rounded-lg text-sm font-bold {{ $paymentStatus['color'] }}">
                    {{ $paymentStatus['label'] }}
                </span>
            </div>
        </div>

    </div>
</div>
@endif


{{--========================================================================================--}}
{{-- MODAL INPUT QUOTE (Hanya untuk Teknisi) --}}
{{--========================================================================================--}}
@if(Auth::user()->role == 'technician' && $order->technician_id == Auth::id())
<div id="quote-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg shadow-2xl w-full max-w-lg p-6 border border-gray-700">
        <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
            <h3 class="text-xl font-bold text-white">Input Service Quote (Order #{{ $order->id }})</h3>
            <button class="text-gray-400 hover:text-white" onclick="document.getElementById('quote-modal').classList.add('hidden'); resetQuoteForm();">
                ×
            </button>
        </div>

        {{-- KRUSIAL: Form Teknisi sekarang menggunakan Form Submission (POST) biasa --}}
        <form id="quote-input-form" action="{{ route('order.save_quote', $order->id) }}" method="POST">
            @csrf

            <h4 class="text-md font-bold text-white mb-3">Parts & Materials</h4>

            <div id="item-container" class="space-y-3 mb-4 max-h-40 overflow-y-auto pr-2">
                {{-- Item list will be appended here by JavaScript --}}
            </div>

            <a href="#" onclick="addItemInput('part'); return false;" class="text-xs text-blue-400 hover:text-blue-300 mb-4 flex items-center font-semibold">
                + Tambah Item/Part
            </a>

            <div class="space-y-3 text-sm">
                {{-- Service Fee (Static Input) --}}
                <div class="flex justify-between items-center bg-gray-700 p-3 rounded-lg">
                    <span>Service Fee (Diagnosis, Repair, Cleaning)</span>
                    <input type="number" name="service_fee" id="service_fee" value="100000" min="0" required
                        class="w-24 bg-gray-900 text-white p-1 rounded text-right focus:outline-none focus:ring-1 focus:ring-blue-500"
                        oninput="calculateTotal()">
                </div>

                {{-- Platform Fee (Static/Hidden Input) --}}
                <div class="flex justify-between items-center bg-gray-700 p-3 rounded-lg">
                    <span>Platform Fee</span>
                    <input type="number" name="platform_fee_amount" id="platform_fee_amount" value="15000" min="0" required
                        class="w-24 bg-gray-900 text-white p-1 rounded text-right focus:outline-none focus:ring-1 focus:ring-blue-500"
                        oninput="calculateTotal()">
                </div>

                <div class="flex justify-between items-center text-lg font-bold border-t border-gray-600 pt-3 mt-3">
                    <span>TOTAL TAGIHAN</span>
                    <span id="subtotal-display" class="text-yellow-400">Rp 115.000</span>
                    <input type="hidden" id="total_price_input" name="total_price" value="115000">
                </div>
                <p id="quote-error" class="text-xs text-red-400"></p>
            </div>

            <button type="submit" class="w-full py-3 mt-6 rounded-xl bg-green-600 hover:bg-green-500 text-white font-bold transition">
                Save & Send Quote to Customer
            </button>
        </form>
    </div>
</div>
@endif


{{--========================================================================================--}}
{{-- MODAL INVOICE (Modal yang muncul saat klik ikon kertas 🧾) --}}
{{--========================================================================================--}}
@if(in_array($order->status, ['quote_sent', 'quote_approved', 'paid', 'en_route', 'service_in_progress', 'service_complete', 'closed']))
<div id="invoice-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg shadow-2xl w-full max-w-lg p-6 border border-gray-700">
        <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
            <h3 class="text-xl font-bold text-white">Payment & Invoice Details</h3>
            <button class="text-gray-400 hover:text-white" onclick="document.getElementById('invoice-modal').classList.add('hidden');">
                ×
            </button>
        </div>

        <div id="invoice-content" class="space-y-4">
            @php
            // Ambil Item berdasarkan tipe
            $serviceFee = $order->items->where('item_type', 'service')->first();
            $platformFee = $order->items->where('item_type', 'platform')->first();
            $parts = $order->items->where('item_type', 'part');

            // Simulasi data VA dan Pay Before
            $vaNumber = '8808 ' . str_pad(rand(10000000, 99999999), 8, '0', STR_PAD_LEFT);
            $payBefore = now()->addHours(24)->format('D, d/m/y, H:i'). ' WIB';

            // Status Pembayaran
            $paymentStatus = match ($order->status) {
            'paid' => ['label' => 'Paid', 'color' => 'bg-green-500 text-black'],
            'quote_approved' => ['label' => 'Payment Pending', 'color' => 'bg-yellow-500 text-black'],
            'quote_sent' => ['label' => 'Awaiting Approval', 'color' => 'bg-yellow-700 text-white'],
            default => ['label' => 'Paid', 'color' => 'bg-green-500 text-black'],
            };
            @endphp

            <div class="flex justify-between items-center">
                <p class="text-sm text-gray-400">Order ID: <span class="text-white">SV-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</span></p>
                <a href="{{ route('order.download_invoice', $order->id) }}" class="bg-gray-700 hover:bg-gray-600 text-white text-xs px-3 py-1 rounded-lg">
                    Download Invoice (TXT)
                </a>
            </div>

            <div class="bg-gray-900 p-4 rounded-xl space-y-3">
                <p class="text-sm text-gray-400">Date: <span class="text-white">{{ $order->created_at->format('M d, Y') }}</span></p>
                <p class="text-sm text-gray-400">Technician: <span class="text-white">{{ $order->technician->name ?? 'N/A' }}</span></p>
            </div>

            <h4 class="font-bold text-white mt-4">Invoice</h4>
            <div class="space-y-2 text-sm">

                {{-- Service Fee --}}
                <div class="flex justify-between text-gray-300">
                    <span>Service Fee</span>
                    <span>Rp {{ number_format($serviceFee->unit_price ?? 0, 0, ',', '.') }}</span>
                </div>

                <h5 class="text-gray-400 font-semibold mt-3">Parts & Materials</h5>
                @forelse($parts as $item)
                <div class="flex justify-between text-gray-300 ml-2">
                    <span>{{ $item->quantity }}x {{ $item->item_name }}</span>
                    <span>Rp {{ number_format($item->quantity * $item->unit_price, 0, ',', '.') }}</span>
                </div>
                @empty
                <div class="text-gray-500 ml-2">No Parts Required</div>
                @endforelse

                {{-- Platform Fee --}}
                <div class="flex justify-between text-gray-300 border-t border-gray-700 pt-2">
                    <span>Platform Fee</span>
                    <span>Rp {{ number_format($platformFee->unit_price ?? 0, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between text-lg font-bold border-t border-gray-600 pt-3">
                    <span>Total Tagihan</span>
                    <span class="text-yellow-400">Rp {{ number_format($order->total_price ?? 0, 0, ',', '.') }}</span>
                </div>
            </div>

            <h4 class="font-bold text-white pt-4">Complete Your Payment</h4>

            {{-- FORM SUBMISSION UNTUK APPROVE (NON-AJAX) --}}
            @if($order->status == 'quote_sent' && Auth::user()->role == 'customer')
            <form action="{{ route('order.approve', $order->id) }}" method="POST">
                @csrf
                <button type="submit" class="w-full py-2 mt-3 rounded-lg bg-green-600 hover:bg-green-500 text-white font-bold transition">
                    Approve Quote & Continue to Payment
                </button>
            </form>
            @endif

            {{-- DETAIL PEMBAYARAN & MARK PAID (NON-AJAX) --}}
            @if($order->status == 'quote_approved')
            <div class="bg-gray-900 p-4 rounded-xl space-y-3">
                <p class="text-sm text-gray-400">Pay Before: <span class="font-bold text-white">{{ $payBefore }}</span></p>
                <p class="text-sm text-gray-400">VA Number: <span class="font-bold text-white">{{ $vaNumber }}</span></p>
                <p class="text-sm text-gray-400">Payment Method: <span class="text-white">BCA, BRI, BTN, Dana, OVO</span></p>

                @if(Auth::user()->role == 'customer')
                <form action="{{ route('order.mark_paid', $order->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2 mt-3 rounded-lg bg-green-600 hover:bg-green-500 text-white font-bold transition">
                        Mark as Paid / Done
                    </button>
                </form>
                @endif
            </div>
            @endif

            {{-- STATUS PEMBAYARAN --}}
            <div class="flex justify-between items-center pt-3 border-t border-gray-700">
                <span class="text-sm text-gray-400">Payment Status:</span>
                <span class="px-3 py-1 rounded-lg text-sm font-bold {{ $paymentStatus['color'] }}">
                    {{ $paymentStatus['label'] }}
                </span>
            </div>
        </div>

    </div>
</div>
@endif


{{--========================================================================================--}}
{{-- MODAL INPUT QUOTE (Hanya untuk Teknisi) --}}
{{--========================================================================================--}}
@if(Auth::user()->role == 'technician' && $order->technician_id == Auth::id())
<div id="quote-modal" class="fixed inset-0 bg-gray-900 bg-opacity-75 hidden flex items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg shadow-2xl w-full max-w-lg p-6 border border-gray-700">
        <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
            <h3 class="text-xl font-bold text-white">Input Service Quote (Order #{{ $order->id }})</h3>
            <button class="text-gray-400 hover:text-white" onclick="document.getElementById('quote-modal').classList.add('hidden'); resetQuoteForm();">
                ×
            </button>
        </div>

        {{-- KRUSIAL: Form Teknisi sekarang menggunakan Form Submission (POST) biasa --}}
        <form id="quote-input-form" action="{{ route('order.save_quote', $order->id) }}" method="POST">
            @csrf

            <h4 class="text-md font-bold text-white mb-3">Parts & Materials</h4>

            <div id="item-container" class="space-y-3 mb-4 max-h-40 overflow-y-auto pr-2">
                {{-- Item list will be appended here by JavaScript --}}
            </div>

            <a href="#" onclick="addItemInput('part'); return false;" class="text-xs text-blue-400 hover:text-blue-300 mb-4 flex items-center font-semibold">
                + Tambah Item/Part
            </a>

            <div class="space-y-3 text-sm">
                {{-- Service Fee (Static Input) --}}
                <div class="flex justify-between items-center bg-gray-700 p-3 rounded-lg">
                    <span>Service Fee (Diagnosis, Repair, Cleaning)</span>
                    <input type="number" name="service_fee" id="service_fee" value="100000" min="0" required
                        class="w-24 bg-gray-900 text-white p-1 rounded text-right focus:outline-none focus:ring-1 focus:ring-blue-500"
                        oninput="calculateTotal()">
                </div>

                {{-- Platform Fee (Static/Hidden Input) --}}
                <div class="flex justify-between items-center bg-gray-700 p-3 rounded-lg">
                    <span>Platform Fee</span>
                    <input type="number" name="platform_fee_amount" id="platform_fee_amount" value="15000" min="0" required
                        class="w-24 bg-gray-900 text-white p-1 rounded text-right focus:outline-none focus:ring-1 focus:ring-blue-500"
                        oninput="calculateTotal()">
                </div>

                <div class="flex justify-between items-center text-lg font-bold border-t border-gray-600 pt-3 mt-3">
                    <span>TOTAL TAGIHAN</span>
                    <span id="subtotal-display" class="text-yellow-400">Rp 115.000</span>
                    <input type="hidden" id="total_price_input" name="total_price" value="115000">
                </div>
                <p id="quote-error" class="text-xs text-red-400"></p>
            </div>

            <button type="submit" class="w-full py-3 mt-6 rounded-xl bg-green-600 hover:bg-green-500 text-white font-bold transition">
                Save & Send Quote to Customer
            </button>
        </form>
    </div>
</div>
@endif


{{--========================================================================================--}}
{{-- JAVASCRIPT (Hanya untuk Quote Calc dan Modal) --}}
{{--========================================================================================--}}
<script>
    // URL dan Konstanta
    const SAVE_QUOTE_URL = '{{ route("order.save_quote", $order->id) }}';
    const PUBLIC_STORAGE_URL = '{{ asset('
    storage ') }}';
    const currentUserId = {
        {
            Auth::id()
        }
    };
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '';

    // --- LOGIKA CHAT DINONAKTIFKAN SESUAI PERMINTAAN ---
    function renderMessage(msg) {
        /* Disederhanakan */
    }
    async function loadChatHistory(orderId) {
        console.log("Chat history loading disabled.");
    }
    async function sendMessage(event, orderId) {
        event.preventDefault();
        console.log("Sending message disabled.");
    }


    // --- QUOTE LOGIC (Teknisi) ---
    let itemCounter = 0;

    function addItemInput(type) {
        itemCounter++;
        const container = document.getElementById('item-container');

        let namePlaceholder = 'Nama Item/Spare Part';
        let typeInput = type;

        const newItemHtml = `
            <div id="item-${itemCounter}" class="flex gap-2 items-center bg-gray-700 p-2 rounded-lg">
                <input type="text" name="items[${itemCounter}][name]" placeholder="${namePlaceholder}" required
                       class="flex-1 p-1 bg-gray-900 text-white rounded focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm">
                <input type="number" name="items[${itemCounter}][qty]" placeholder="Qty" value="1" min="1" required
                       class="w-12 p-1 bg-gray-900 text-white rounded text-center focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                       oninput="calculateTotal()">
                <input type="number" name="items[${itemCounter}][price]" placeholder="Harga Satuan" min="0" required
                       class="w-28 p-1 bg-gray-900 text-white rounded text-right focus:outline-none focus:ring-1 focus:ring-blue-500 text-sm"
                       oninput="calculateTotal()">
                <input type="hidden" name="items[${itemCounter}][type]" value="${typeInput}">
                <button type="button" onclick="removeItemInput(${itemCounter})" class="text-red-400 hover:text-red-300 text-sm">
                    ×
                </button>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newItemHtml);
        calculateTotal();
    }

    function removeItemInput(id) {
        document.getElementById(`item-${id}`).remove();
        calculateTotal();
    }

    function calculateTotal() {
        let totalParts = 0;

        const itemElements = document.querySelectorAll('#item-container > div');

        itemElements.forEach(item => {
            const qtyElement = item.querySelector('input[name$="[qty]"]');
            const priceElement = item.querySelector('input[name$="[price]"]');

            const qty = parseFloat(qtyElement ? qtyElement.value : 0) || 0;
            const price = parseFloat(priceElement ? priceElement.value : 0) || 0;

            totalParts += qty * price;
        });

        const serviceFeeInput = document.getElementById('service_fee');
        const platformFeeInput = document.getElementById('platform_fee_amount');

        const serviceFee = parseFloat(serviceFeeInput ? serviceFeeInput.value : 0) || 0;
        const platformFee = parseFloat(platformFeeInput ? platformFeeInput.value : 0) || 0;

        const finalTotal = totalParts + serviceFee + platformFee;

        const subtotalDisplay = document.getElementById('subtotal-display');
        const totalPriceInput = document.getElementById('total_price_input');

        if (subtotalDisplay) subtotalDisplay.innerText = formatRupiah(finalTotal);
        if (totalPriceInput) totalPriceInput.value = finalTotal;
    }

    function formatRupiah(number) {
        if (isNaN(number)) return 'Rp 0';
        return 'Rp ' + number.toLocaleString('id-ID');
    }

    function resetQuoteForm() {
        document.getElementById('item-container').innerHTML = '';
        itemCounter = 0;
        calculateTotal();
    }

    document.addEventListener('DOMContentLoaded', calculateTotal);

    // --- SUBMIT QUOTE (Teknisi) ---
    // Fungsi ini dikosongkan karena form sudah menggunakan method POST di HTML
    async function submitQuote(event, orderId) {
        // Form submission sekarang ditangani oleh HTML.
        // Hapus preventDefault jika ingin tombol Submit form berfungsi normal.
        // Jika Anda ingin validasi di sini, Anda harus menggunakan AJAX.
    }

    // --- LOGIKA APPROVE/PAID TELAH DIPINDAHKAN KE CONTROLLER (NON-AJAX) ---
</script>