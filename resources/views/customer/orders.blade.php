@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-3xl font-bold mb-8">My Orders ({{ $orders->count() }} Orders)</h2>

    @if($orders->isEmpty())
    <div class="p-10 text-center bg-gray-800 rounded-2xl border border-gray-700">
        <p class="text-gray-400">Anda belum membuat pesanan servis apa pun.</p>
        <a href="{{ route('customer.categories') }}" class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-500 transition">
            Pesan Servis Sekarang
        </a>
    </div>
    @else
    <div class="space-y-6">
        @foreach($orders as $order)
        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 hover:border-blue-500 transition shadow-lg flex justify-between items-center">

            <div>
                <h3 class="font-bold text-xl mb-1">{{ $order->appliance_name }}</h3>
                <p class="text-sm text-gray-400 mb-2">Category: {{ $order->category }}</p>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold 
                        {{ $order->status == 'pending' ? 'bg-yellow-500/20 text-yellow-500' : 'bg-green-500/20 text-green-500' }}">
                        {{ strtoupper(str_replace('_', ' ', $order->status)) }}
                    </span>
                    @if($order->technician)
                    <span class="text-xs text-blue-400">Assigned to: {{ $order->technician->name }}</span>
                    @else
                    <span class="text-xs text-red-400">Waiting for technician acceptance</span>
                    @endif
                </div>
            </div>

            <a href="{{ route('order.show', $order->id) }}" class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-bold transition">
                See Details
            </a>

        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection