@extends('layouts.main')

@section('content')
<div class="max-w-3xl mx-auto pb-10">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-white">{{ $displayTitle }}</h1>
        <a href="{{ route('customer.categories') }}" class="bg-white text-black p-2 rounded-full hover:bg-gray-200 transition">
            ⬅️
        </a>
    </div>

    <div class="space-y-4">
        @if($technicians->isEmpty())
        <div class="text-center py-10 text-gray-500 bg-gray-800 rounded-xl border border-gray-700">
            <p>Belum ada teknisi di kategori ini.</p>
            <p class="text-xs mt-2">Coba kategori lain atau kembali lagi nanti.</p>
        </div>
        @else
        @foreach($technicians as $tech)

        <div class="bg-gray-800 p-4 rounded-xl flex items-center gap-4 text-white border border-gray-700 shadow-lg">

            <div class="flex-shrink-0">
                @if($tech->avatar)
                <img src="{{ asset('storage/' . $tech->avatar) }}" class="w-16 h-16 rounded-2xl object-cover bg-gray-300 border border-gray-600">
                @else
                <div class="w-16 h-16 rounded-2xl bg-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                    {{ substr($tech->name, 0, 1) }}
                </div>
                @endif
            </div>

            <div class="flex-grow">
                <h3 class="font-bold text-lg">{{ $tech->name }}</h3>
                <p class="text-xs text-gray-400">Category: Technician / Specialist</p>

                <div class="flex text-yellow-400 text-sm mt-1">
                    {{-- Rating Placeholder (Ganti jika sudah ada logika rating) --}}
                    <span>⭐️⭐️⭐️⭐️⭐️ (5.0)</span>
                </div>
            </div>

            <div>
                <a href="{{ route('customer.technician.show', ['slug' => $slug, 'id' => $tech->id]) }}"
                    class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg text-sm font-bold transition shadow-md">
                    See Profile
                </a>
            </div>

        </div>
        @endforeach
        @endif
    </div>

</div>
@endsection