@extends('layouts.main')

@section('content')
<div class="max-w-5xl mx-auto space-y-8">

    <div class="relative bg-gradient-to-r from-gray-800 to-black rounded-2xl p-10 overflow-hidden border border-gray-700 shadow-lg">
        <div class="relative z-10 w-2/3">
            <h2 class="text-4xl font-bold text-white mb-2">Rumah Beres</h2>
            <p class="text-gray-300 mb-6 text-lg">Wujudkan Servis Perabotan Tanpa Drama!</p>

            <a href="{{ route('customer.categories') }}" class="inline-block bg-white text-black font-bold py-3 px-8 rounded-full hover:bg-gray-200 transition shadow-lg transform hover:-translate-y-1">
                Book Now
            </a>
        </div>
        <div class="absolute right-0 bottom-0 h-full w-1/3 bg-gray-700 opacity-30 flex items-center justify-center text-4xl">
            🛠️
        </div>
    </div>

    <div>
        <h3 class="text-2xl font-bold mb-6">Categories</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">

            <a href="{{ route('customer.category.show', 'cooling') }}" class="bg-gray-800 p-6 rounded-2xl text-center hover:bg-gray-700 transition border border-gray-700 group block">
                <div class="text-5xl mb-4 transform group-hover:scale-110 transition duration-300">❄️</div>
                <div class="text-lg font-bold mb-1">Cooling</div>
                <p class="text-xs text-gray-400 mb-4">AC & Air Treatment</p>
                <span class="inline-block border border-gray-500 text-xs px-4 py-1 rounded-full hover:bg-white hover:text-black transition">See Profile</span>
            </a>

            <a href="{{ route('customer.category.show', 'entertainment') }}" class="bg-gray-800 p-6 rounded-2xl text-center hover:bg-gray-700 transition border border-gray-700 group block">
                <div class="text-5xl mb-4 transform group-hover:scale-110 transition duration-300">📺</div>
                <div class="text-lg font-bold mb-1">Entertainment</div>
                <p class="text-xs text-gray-400 mb-4">TV & Audio</p>
                <span class="inline-block border border-gray-500 text-xs px-4 py-1 rounded-full hover:bg-white hover:text-black transition">See Profile</span>
            </a>

            <a href="{{ route('customer.category.show', 'laundry') }}" class="bg-gray-800 p-6 rounded-2xl text-center hover:bg-gray-700 transition border border-gray-700 group block">
                <div class="text-5xl mb-4 transform group-hover:scale-110 transition duration-300">🧺</div>
                <div class="text-lg font-bold mb-1">Laundry</div>
                <p class="text-xs text-gray-400 mb-4">Washing Machine</p>
                <span class="inline-block border border-gray-500 text-xs px-4 py-1 rounded-full hover:bg-white hover:text-black transition">See Profile</span>
            </a>

            <a href="{{ route('customer.category.show', 'kitchen') }}" class="bg-gray-800 p-6 rounded-2xl text-center hover:bg-gray-700 transition border border-gray-700 group block">
                <div class="text-5xl mb-4 transform group-hover:scale-110 transition duration-300">🍳</div>
                <div class="text-lg font-bold mb-1">Kitchen</div>
                <p class="text-xs text-gray-400 mb-4">Stove & Oven</p>
                <span class="inline-block border border-gray-500 text-xs px-4 py-1 rounded-full hover:bg-white hover:text-black transition">See Profile</span>
            </a>

            <a href="{{ route('customer.category.show', 'computing') }}" class="bg-gray-800 p-6 rounded-2xl text-center hover:bg-gray-700 transition border border-gray-700 group block">
                <div class="text-5xl mb-4 transform group-hover:scale-110 transition duration-300">💻</div>
                <div class="text-lg font-bold mb-1">Computing</div>
                <p class="text-xs text-gray-400 mb-4">Laptop & PC</p>
                <span class="inline-block border border-gray-500 text-xs px-4 py-1 rounded-full hover:bg-white hover:text-black transition">See Profile</span>
            </a>

            <a href="{{ route('customer.category.show', 'water') }}" class="bg-gray-800 p-6 rounded-2xl text-center hover:bg-gray-700 transition border border-gray-700 group block">
                <div class="text-5xl mb-4 transform group-hover:scale-110 transition duration-300">🚰</div>
                <div class="text-lg font-bold mb-1">Water</div>
                <p class="text-xs text-gray-400 mb-4">Pumps & Heaters</p>
                <span class="inline-block border border-gray-500 text-xs px-4 py-1 rounded-full hover:bg-white hover:text-black transition">See Profile</span>
            </a>

        </div>
    </div>

    <div>
        <h3 class="text-2xl font-bold mb-6">My Active Orders</h3>
        @if($my_orders->isEmpty())
        <div class="p-8 text-center bg-gray-800 rounded-2xl border border-gray-700 text-gray-400">
            <p>Belum ada pesanan aktif.</p>
        </div>
        @else
        <div class="space-y-4">
            @foreach($my_orders as $order)
            <div class="bg-gray-800 p-6 rounded-2xl flex justify-between items-center border border-gray-700 hover:border-gray-500 transition">
                <div class="flex items-center gap-6">
                    <div class="h-16 w-16 bg-gray-700 rounded-xl flex items-center justify-center text-3xl">
                        🛠️
                    </div>
                    <div>
                        <h4 class="font-bold text-white text-lg">{{ $order->appliance_name }}</h4>
                        <p class="text-sm text-gray-400">{{ $order->description }}</p>
                        <span class="text-xs text-gray-500 mt-1 block">Category: {{ $order->category }}</span>
                    </div>
                </div>

                <div class="text-right">
                    <span class="px-4 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $order->status == 'pending' ? 'bg-yellow-500/20 text-yellow-500' : 'bg-green-500/20 text-green-500' }}">
                        {{ $order->status }}
                    </span>
                    <p class="text-lg font-bold mt-2">Rp {{ number_format($order->total_price) }}</p>

                    <a href="{{ route('order.show', $order->id) }}" class="inline-block mt-3 text-sm text-blue-400 hover:text-blue-300 font-semibold hover:underline">
                        View Detail & Timeline &rarr;
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>
@endsection