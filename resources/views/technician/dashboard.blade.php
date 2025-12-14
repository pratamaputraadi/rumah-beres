@extends('layouts.main')

@section('content')
<div class="max-w-5xl mx-auto">

    @if(session('success'))
    <div class="bg-green-900/50 border border-green-700 text-green-300 p-3 rounded-lg mb-6 text-sm">
        {{ session('success') }}
    </div>
    @endif

    <h2 class="text-2xl font-bold mb-6">Professional Dashboard</h2>

    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-800 p-6 rounded-2xl border border-green-500 text-center shadow-[0_0_15px_rgba(34,197,94,0.5)]">
            <p class="text-gray-400 text-sm mb-1">Accepted Jobs</p>
            <h3 class="text-4xl font-bold text-white">{{ $accepted_jobs_count ?? 0 }}</h3>
        </div>

        <div class="bg-gray-800 p-6 rounded-2xl border border-blue-500 text-center shadow-[0_0_15px_rgba(59,130,246,0.5)]">
            <p class="text-blue-400 text-sm mb-1">Incoming Requests</p>
            <h3 class="text-4xl font-bold text-white">{{ $available_requests_count ?? 0 }}</h3>
        </div>

        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 text-center">
            <p class="text-gray-400 text-sm mb-1">Cancelled</p>
            <h3 class="text-4xl font-bold text-red-500">{{ $cancelled_jobs_count ?? 0 }}</h3>
        </div>
    </div>

    <h3 class="text-xl font-bold mb-4">Incoming Job Requests</h3>

    @if($available_requests->isEmpty())
    <div class="p-8 text-center bg-gray-800 rounded-xl border border-gray-700 mb-8">
        <p class="text-gray-400">Belum ada job baru yang masuk.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        @foreach($available_requests as $req)

        @php
        $isTargeted = $req->technician_id == Auth::id();
        @endphp

        <div class="bg-gray-800 p-6 rounded-2xl border {{ $isTargeted ? 'border-red-500' : 'border-gray-700' }} hover:border-blue-500 transition">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-gray-600 rounded-full flex items-center justify-center">👤</div>
                    <div>
                        <h4 class="font-bold text-white">{{ $req->customer->name }}</h4>
                        <p class="text-xs text-gray-400">Customer</p>
                    </div>
                </div>

                @if ($isTargeted)
                <span class="text-xs bg-red-900/50 text-red-300 px-2 py-1 rounded border border-red-700 font-bold">REQUEST TARGETED</span>
                @else
                <span class="text-xs bg-gray-700 px-2 py-1 rounded text-gray-300">AVAILABLE PUBLIC</span>
                @endif

            </div>

            <div class="bg-gray-900 p-4 rounded-xl mb-4">
                <p class="text-xs text-blue-400 uppercase font-semibold mb-1">{{ $req->category }}</p>
                <h5 class="font-bold text-lg mb-1">{{ $req->appliance_name }}</h5>
                <p class="text-sm text-gray-400 truncate">{{ $req->description }}</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('order.show', $req->id) }}" class="flex-1 py-2 rounded-lg border border-gray-600 hover:bg-gray-700 text-sm text-center">
                    See Details
                </a>
                <form action="{{ route('booking.update', $req->id) }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="action" value="accept">
                    <button type="submit" class="w-full py-2 rounded-lg bg-green-600 hover:bg-green-500 text-white text-sm font-bold transition">
                        Accept Job
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection