@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-4xl mx-auto">

    <a href="{{ route('admin.verification.list') }}" class="flex items-center text-gray-400 hover:text-white mb-6 transition">
        <span class="mr-2">&larr;</span> Back to Queue
    </a>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="space-y-6">
            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                <h2 class="text-xl font-bold mb-4 text-white">Technician Profile</h2>

                <div class="mb-4">
                    <label class="text-xs text-gray-500 uppercase font-bold">Full Name</label>
                    <p class="text-lg font-bold text-white">{{ $tech->name }}</p>
                </div>

                <div class="mb-4">
                    <label class="text-xs text-gray-500 uppercase font-bold">Email</label>
                    <p class="text-white text-sm">{{ $tech->email }}</p>
                </div>

                <div class="mb-4">
                    <label class="text-xs text-gray-500 uppercase font-bold">Specialization</label>
                    <div class="mt-1">
                        <span class="px-3 py-1 bg-blue-900 text-blue-300 rounded-full text-xs font-bold border border-blue-700">
                            {{ $tech->specialization }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="text-xs text-gray-500 uppercase font-bold">Address</label>
                    <p class="text-gray-300 text-sm">{{ $tech->address ?? 'Alamat belum diisi' }}</p>
                </div>

                <div class="mb-4">
                    <label class="text-xs text-gray-500 uppercase font-bold">Joined Date</label>
                    <p class="text-gray-400 text-sm">
                        {{ $tech->created_at->format('d M Y, H:i:s') }}
                        <span class="text-[10px] text-gray-600">(WIB/UTC)</span>
                    </p>
                </div>

            </div>

            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                <h3 class="font-bold text-white mb-4">Verification Action</h3>
                <div class="flex flex-col gap-3">

                    <form action="{{ route('admin.verify', $tech->id) }}" method="POST" id="approve-form">
                        @csrf
                        <input type="hidden" name="action" value="approve">
                        <button type="button" onclick="confirmApprove()" class="w-full py-3 bg-green-600 hover:bg-green-500 text-white font-bold rounded-xl transition shadow-lg flex justify-center items-center gap-2">
                            <span>✅</span> Approve Technician
                        </button>
                    </form>

                    <form action="{{ route('admin.verify', $tech->id) }}" method="POST" id="reject-form">
                        @csrf
                        <input type="hidden" name="action" value="reject">
                        <button type="button" onclick="confirmReject()" class="w-full py-3 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl transition shadow-lg flex justify-center items-center gap-2">
                            <span>🗑️</span> Reject & Delete
                        </button>
                    </form>

                </div>
            </div>
        </div>

        <div class="md:col-span-2 space-y-6">

            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                <h2 class="text-xl font-bold mb-4 text-white flex items-center gap-2">
                    <span>🆔</span> Identity Card (KTP)
                </h2>

                <div class="bg-gray-900 p-2 rounded-xl border border-gray-600 overflow-hidden">
                    @if($tech->ktp_photo)
                    <img src="{{ asset('storage/' . $tech->ktp_photo) }}" alt="KTP {{ $tech->name }}" class="w-full h-auto rounded-lg object-contain">
                    @else
                    <div class="h-64 flex flex-col items-center justify-center text-gray-500">
                        <span class="text-4xl mb-2">🚫</span>
                        <p>No KTP Uploaded</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 shadow-lg">
                <h2 class="text-xl font-bold mb-4 text-white flex items-center gap-2">
                    <span>📜</span> Professional Certificate
                </h2>

                <div class="bg-gray-900 p-2 rounded-xl border border-gray-600 overflow-hidden">
                    @if($tech->certificate_photo)
                    <img src="{{ asset('storage/' . $tech->certificate_photo) }}" alt="Certificate" class="w-full h-auto rounded-lg object-contain">
                    @else
                    <div class="h-32 flex flex-col items-center justify-center text-gray-500 opacity-50">
                        <span class="text-3xl mb-2">🚫</span>
                        <p class="text-sm">No Certificate Uploaded</p>
                    </div>
                    @endif
                </div>
            </div>

        </div>

    </div>
</div>

<script>
    // Konfirmasi Approve
    function confirmApprove() {
        Swal.fire({
            title: 'Setujui Teknisi ini?',
            text: "Teknisi akan langsung aktif dan bisa menerima order.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#16a34a', // Hijau
            cancelButtonColor: '#374151', // Abu gelap
            confirmButtonText: 'Ya, Approve!',
            cancelButtonText: 'Batal',
            background: '#1f2937',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('approve-form').submit();
            }
        })
    }

    // Konfirmasi Reject
    function confirmReject() {
        Swal.fire({
            title: 'Tolak & Hapus Akun?',
            text: "Aksi ini akan menghapus data teknisi secara permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626', // Merah
            cancelButtonColor: '#374151', // Abu gelap
            confirmButtonText: 'Ya, Tolak & Hapus',
            cancelButtonText: 'Batal',
            background: '#1f2937',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('reject-form').submit();
            }
        })
    }
</script>
@endsection