@extends('layouts.main')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white">Service Categories</h1>
        <p class="text-gray-400 mt-2">Pilih kategori layanan yang Anda butuhkan.</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">

        <a href="{{ route('customer.category.show', 'cooling') }}" class="bg-gray-800 p-8 rounded-2xl text-center border border-gray-700 hover:border-gray-500 hover:bg-gray-750 transition cursor-pointer group block">
            <div class="text-6xl mb-4 transform group-hover:scale-110 transition duration-300">❄️</div>
            <h3 class="text-lg font-bold text-white">Cooling & Air Treatment</h3>
            <p class="text-xs text-gray-400 mt-1">AC, Kipas Angin, Air Purifier</p>
        </a>

        <a href="{{ route('customer.category.show', 'entertainment') }}" class="bg-gray-800 p-8 rounded-2xl text-center border border-gray-700 hover:border-gray-500 hover:bg-gray-750 transition cursor-pointer group block">
            <div class="text-6xl mb-4 transform group-hover:scale-110 transition duration-300">📺</div>
            <h3 class="text-lg font-bold text-white">Entertainment Appliances</h3>
            <p class="text-xs text-gray-400 mt-1">TV, Home Theater, Speaker</p>
        </a>

        <a href="{{ route('customer.category.show', 'laundry') }}" class="bg-gray-800 p-8 rounded-2xl text-center border border-gray-700 hover:border-gray-500 hover:bg-gray-750 transition cursor-pointer group block">
            <div class="text-6xl mb-4 transform group-hover:scale-110 transition duration-300">🧺</div>
            <h3 class="text-lg font-bold text-white">Laundry Appliances</h3>
            <p class="text-xs text-gray-400 mt-1">Mesin Cuci, Pengering, Setrika</p>
        </a>

        <a href="{{ route('customer.category.show', 'kitchen') }}" class="bg-gray-800 p-8 rounded-2xl text-center border border-gray-700 hover:border-gray-500 hover:bg-gray-750 transition cursor-pointer group block">
            <div class="text-6xl mb-4 transform group-hover:scale-110 transition duration-300">🍳</div>
            <h3 class="text-lg font-bold text-white">Kitchen Appliances</h3>
            <p class="text-xs text-gray-400 mt-1">Kompor, Kulkas, Oven, Blender</p>
        </a>

        <a href="{{ route('customer.category.show', 'computing') }}" class="bg-gray-800 p-8 rounded-2xl text-center border border-gray-700 hover:border-gray-500 hover:bg-gray-750 transition cursor-pointer group block">
            <div class="text-6xl mb-4 transform group-hover:scale-110 transition duration-300">💻</div>
            <h3 class="text-lg font-bold text-white">Computing & Home Office</h3>
            <p class="text-xs text-gray-400 mt-1">Laptop, PC, Printer, WiFi</p>
        </a>

        <a href="{{ route('customer.category.show', 'water') }}" class="bg-gray-800 p-8 rounded-2xl text-center border border-gray-700 hover:border-gray-500 hover:bg-gray-750 transition cursor-pointer group block">
            <div class="text-6xl mb-4 transform group-hover:scale-110 transition duration-300">🚰</div>
            <h3 class="text-lg font-bold text-white">Water Pumps & Heaters</h3>
            <p class="text-xs text-gray-400 mt-1">Pompa Air, Water Heater</p>
        </a>

    </div>

</div>
@endsection