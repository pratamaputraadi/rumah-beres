@extends('layouts.main')

@section('content')
<div class="max-w-5xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Accepted Jobs & Progress</h2>

    <div class="grid grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-800 p-6 rounded-2xl border border-green-500 text-center shadow-[0_0_15px_rgba(34,197,94,0.5)]">
            <p class="text-gray-400 text-sm mb-1">Accepted Jobs</p>
            <h3 class="text-4xl font-bold text-white">{{ $accepted_jobs_count ?? 0 }}</h3>
        </div>

        <div class="bg-gray-800 p-6 rounded-2xl border border-blue-500 text-center">
            <p class="text-blue-400 text-sm mb-1">Incoming Requests</p>
            <h3 class="text-4xl font-bold text-white">{{ $available_requests_count ?? 0 }}</h3>
        </div>

        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 text-center">
            <p class="text-gray-400 text-sm mb-1">Cancelled</p>
            <h3 class="text-4xl font-bold text-red-500">{{ $cancelled_jobs_count ?? 0 }}</h3>
        </div>
    </div>

    <h3 class="text-xl font-bold mb-4 text-green-400">Current Workload ({{ $my_jobs->count() }} jobs)</h3>

    @if($my_jobs->isEmpty())
    <div class="p-8 text-center bg-gray-800 rounded-xl border border-gray-700">
        <p class="text-gray-400">Anda belum memiliki job yang diterima.</p>
    </div>
    @else
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($my_jobs as $job)

        @php
        $isClosed = $job->status == 'closed';
        $borderColor = $isClosed ? 'border-red-600' : 'border-green-500 hover:border-blue-500';
        $badgeColor = $isClosed ? 'bg-red-900/50 text-red-300 border-red-700' : 'bg-blue-900/50 text-blue-300 border-blue-700';
        $buttonColor = $isClosed ? 'bg-red-600 hover:bg-red-500' : 'bg-green-600 hover:bg-green-500';
        @endphp

        <div class="bg-gray-800 p-6 rounded-2xl border {{ $borderColor }} transition">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-gray-600 rounded-full flex items-center justify-center">👤</div>
                    <div>
                        <h4 class="font-bold text-white">{{ $job->customer->name }}</h4>
                        <p class="text-xs text-gray-400">Customer</p>
                    </div>
                </div>

                <span class="text-xs px-2 py-1 rounded-full font-bold border {{ $badgeColor }}">
                    {{ strtoupper(str_replace('_', ' ', $job->status)) }}
                </span>

            </div>

            <div class="bg-gray-900 p-4 rounded-xl mb-4">
                <p class="text-xs text-blue-400 uppercase font-semibold mb-1">{{ $job->category }}</p>
                <h5 class="font-bold text-lg mb-1">{{ $job->appliance_name }}</h5>
                <p class="text-sm text-gray-400 truncate">{{ $job->description }}</p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('order.show', $job->id) }}" class="w-full py-2 rounded-lg {{ $buttonColor }} text-white text-sm font-bold transition">
                    See Details (Timeline)
                </a>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection