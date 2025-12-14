@extends('layouts.main')

@section('content')
<div class="max-w-6xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Technician Verification Queue</h2>
        <span class="bg-yellow-600 text-white px-3 py-1 rounded-full text-xs font-bold">
            Pending: {{ $pending_techs->count() }}
        </span>
    </div>

    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
        <table class="w-full text-left text-gray-400">
            <thead class="bg-gray-900 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-3">Name / Email</th>
                    <th class="px-6 py-3">Specialization</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($pending_techs as $tech)
                <tr class="hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4">
                        <div class="font-bold text-white">{{ $tech->name }}</div>
                        <div class="text-sm text-gray-500">{{ $tech->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-300">{{ $tech->specialization }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-yellow-500/10 text-yellow-500 rounded text-xs font-bold border border-yellow-500/20">
                            Waiting Review
                        </span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <a href="{{ route('admin.show_tech', $tech->id) }}" class="bg-blue-600 hover:bg-blue-500 text-white text-xs font-bold px-4 py-2 rounded-full transition shadow">
                            Review Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <span class="text-4xl mb-2">✅</span>
                            <p>Tidak ada antrian. Semua teknisi sudah diverifikasi.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection