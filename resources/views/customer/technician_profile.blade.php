@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-3xl font-bold text-white tracking-wide">{{ $technician->name }}</h2>
        <a href="{{ route('customer.category.show', $technician->specialization) }}" class="flex items-center text-gray-400 hover:text-white transition">
            <svg class="w-6 h-6 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back
        </a>
    </div>

    <div class="relative w-full h-96 mb-8 rounded-xl overflow-hidden shadow-2xl">
        {{-- Ganti dengan gambar teknisi jika ada, atau placeholder --}}
        <img src="{{ $technician->avatar ? asset('storage/' . $technician->avatar) : 'https://picsum.photos/800/600?random=1' }}"
            alt="{{ $technician->name }}"
            class="w-full h-full object-cover filter brightness-75">

        <div class="absolute inset-0 flex items-center justify-center">
            {{-- Tombol Booking yang mengarah ke form dengan pre-filled data --}}
            <a href="{{ route('booking.create', $technician->id) }}"
                class="bg-white text-gray-900 font-bold py-3 px-12 rounded-full shadow-xl hover:bg-gray-200 transition transform hover:scale-105">
                Booking
            </a>
        </div>
    </div>

    <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg space-y-8">

        <div>
            <h3 class="text-2xl font-bold text-white mb-3">About Me</h3>
            <p class="text-gray-300 leading-relaxed">
                {{ $technician->bio ?? 'Teknisi ini belum mengisi deskripsi diri (Bio). Saya berspesialisasi dalam perbaikan dan instalasi ' . $technician->specialization . '.' }}
            </p>
        </div>

        <div class="border-t border-gray-700 pt-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-2xl font-bold text-white">Ratings</h3>
                <div class="text-xl font-bold text-yellow-400 flex items-center">
                    <span>{{ $average_rating }}/5.0</span>
                    <span class="ml-2">
                        {{-- Logika sederhana untuk bintang berdasarkan $average_rating --}}
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="{{ $i <= round($average_rating) ? 'text-yellow-400' : 'text-gray-600' }}">★</span>
                    @endfor
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                @forelse ($ratings as $rating)
                <div class="bg-gray-900 p-4 rounded-lg border border-gray-700">
                    <div class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center text-xs font-bold mr-3">{{ substr($rating->user, 0, 1) }}</div>
                            <div>
                                <p class="font-semibold text-white">{{ $rating->user }}</p>
                                <p class="text-xs text-gray-500">{{ $rating->city }}</p>
                            </div>
                        </div>
                        <div class="text-sm font-semibold text-yellow-400 flex items-center">
                            {{ number_format($rating->rating, 1) }} ⭐️
                        </div>
                    </div>
                    <p class="text-sm text-gray-300 italic">{{ $rating->review }}</p>
                </div>
                @empty
                <p class="text-center text-gray-500 p-4">Belum ada ulasan untuk teknisi ini.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection