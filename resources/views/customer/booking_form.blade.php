@extends('layouts.main')

@section('content')
<div class="max-w-2xl mx-auto">

    <div class="mb-6">
        <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white flex items-center gap-2">
            <span>&larr;</span> Kembali ke Dashboard
        </a>
    </div>

    <h2 class="text-2xl font-bold mb-6">
        Booking Service Baru
        @if(isset($technician))
        <span class="text-blue-400">({{ $technician->name }})</span>
        @endif
    </h2>

    @if(session('error'))
    <div class="bg-red-900/50 border border-red-700 text-red-300 p-3 rounded-lg mb-4 text-sm">
        {{ session('error') }}
    </div>
    @endif

    <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-xl">

        <form action="{{ route('booking.store') }}" method="POST">
            @csrf

            @if(isset($technician))
            <input type="hidden" name="technician_id" value="{{ $technician->id }}">
            @endif

            <div class="mb-6">
                <label class="block text-gray-400 mb-2 font-bold text-sm">Nama Barang / Perabotan</label>
                <input type="text" name="appliance_name"
                    class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none"
                    placeholder="Contoh: AC Samsung 1PK" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-400 mb-2 font-bold text-sm">Kategori</label>

                @if(isset($technician))
                <input type="hidden" name="category" value="{{ $technician->specialization }}">

                <input type="text"
                    value="{{ $technician->specialization }}"
                    class="w-full bg-gray-700 text-white rounded-lg p-3 mt-1 border border-gray-600 cursor-not-allowed"
                    disabled>
                <p class="text-xs text-blue-400 mt-1">Kategori terkunci karena booking ditujukan langsung kepada Teknisi {{ $technician->name }}.</p>
                @else
                <select name="category"
                    class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none" required>
                    <option value="">Pilih Kategori Servis</option>
                    @foreach($categories as $cat)
                    <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
                @endif
            </div>

            <div class="mb-8">
                <label class="block text-gray-400 mb-2 font-bold text-sm">Keluhan / Masalah</label>
                <textarea name="description" rows="4"
                    class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 focus:outline-none"
                    placeholder="Jelaskan kerusakan barang Anda..." required></textarea>
                <p class="text-xs text-gray-500 mt-2">*Teknisi akan membawa alat berdasarkan deskripsi ini.</p>
            </div>

            <button type="submit" class="w-full py-3 rounded-lg bg-blue-600 text-white font-bold hover:bg-blue-500 transition shadow-lg">
                Book Now
            </button>

        </form>
    </div>
</div>
@endsection