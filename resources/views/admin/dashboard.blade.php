@extends('layouts.main')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white">Admin Control Center</h2>
            <p class="text-gray-400 text-sm">Overview performa aplikasi hari ini.</p>
        </div>
        <span class="px-3 py-1 bg-green-900 text-green-400 rounded-full text-xs font-bold border border-green-700">System Online</span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 hover:border-red-500 transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-xs uppercase font-bold">Admins</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ $countAdmin }}</h3>
                </div>
                <div class="p-2 bg-red-900/30 text-red-400 rounded-lg group-hover:bg-red-600 group-hover:text-white transition">🛡️</div>
            </div>
        </div>

        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 hover:border-blue-500 transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-xs uppercase font-bold">Customers</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ $countCustomer }}</h3>
                </div>
                <div class="p-2 bg-blue-900/30 text-blue-400 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition">👤</div>
            </div>
        </div>

        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 hover:border-purple-500 transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-xs uppercase font-bold">Technicians</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ $countTech }}</h3>
                </div>
                <div class="p-2 bg-purple-900/30 text-purple-400 rounded-lg group-hover:bg-purple-600 group-hover:text-white transition">🔧</div>
            </div>
        </div>

        <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 hover:border-green-500 transition group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-400 text-xs uppercase font-bold">Total Orders</p>
                    <h3 class="text-3xl font-bold text-white mt-1">{{ $totalOrders }}</h3>
                </div>
                <div class="p-2 bg-green-900/30 text-green-400 rounded-lg group-hover:bg-green-600 group-hover:text-white transition">📦</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6">
        <div class="bg-gray-800 p-6 rounded-xl border {{ $pendingCount > 0 ? 'border-yellow-500' : 'border-gray-700' }}">
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-white">Pending Verification</h3>
                    <p class="text-sm text-gray-400">Jumlah teknisi baru yang menunggu persetujuan Anda.</p>
                </div>
                <div class="text-right">
                    <span class="text-4xl font-bold {{ $pendingCount > 0 ? 'text-yellow-400' : 'text-gray-600' }}">{{ $pendingCount }}</span>
                </div>
            </div>

            @if($pendingCount > 0)
            <div class="mt-4 pt-4 border-t border-gray-700">
                <a href="{{ route('admin.verification.list') }}" class="text-yellow-400 hover:text-yellow-300 text-sm font-bold flex items-center gap-2">
                    Periksa Antrian Sekarang &rarr;
                </a>
            </div>
            @else
            <div class="mt-4 pt-4 border-t border-gray-700">
                <span class="text-green-500 text-sm flex items-center gap-2">✓ Semua aman terkendali.</span>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection