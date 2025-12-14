@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-4xl mx-auto">
    <a href="{{ route('admin.users.index') }}" class="text-gray-400 hover:text-white mb-6 block transition">&larr; Back to List</a>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="bg-gray-800 p-6 rounded-2xl border border-gray-700 text-center shadow-lg">
            <div class="h-32 w-32 rounded-full bg-gray-700 mx-auto overflow-hidden mb-4 border-4 border-gray-600">
                @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" class="h-full w-full object-cover">
                @else
                <div class="h-full w-full flex items-center justify-center text-4xl font-bold text-white bg-blue-600">
                    {{ substr($user->name, 0, 1) }}
                </div>
                @endif
            </div>
            <h2 class="text-xl font-bold text-white">{{ $user->name }}</h2>
            <p class="text-gray-400 text-sm">{{ $user->email }}</p>
            <div class="mt-4">
                <span class="px-3 py-1 rounded-full text-sm font-bold uppercase 
                    {{ $user->role == 'admin' ? 'bg-red-900 text-red-300 border border-red-700' : ($user->role == 'technician' ? 'bg-purple-900 text-purple-300 border border-purple-700' : 'bg-blue-900 text-blue-300 border border-blue-700') }}">
                    {{ $user->role }}
                </span>
            </div>
        </div>

        <div class="md:col-span-2 bg-gray-800 p-6 rounded-2xl border border-gray-700 space-y-6 shadow-lg">

            <div>
                <label class="text-xs text-gray-500 uppercase font-bold">Bio</label>
                <p class="text-gray-300 bg-gray-900/50 p-3 rounded-lg border border-gray-700 mt-1">
                    {{ $user->bio ?? 'User belum mengisi bio.' }}
                </p>
            </div>

            <div>
                <label class="text-xs text-gray-500 uppercase font-bold">Address</label>
                <p class="text-gray-300 bg-gray-900/50 p-3 rounded-lg border border-gray-700 mt-1">
                    {{ $user->address ?? '-' }}
                </p>
            </div>

            @if($user->role == 'technician')
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-700">
                <div>
                    <label class="text-xs text-gray-500 uppercase font-bold">Specialization</label>
                    <p class="text-white">{{ $user->specialization }}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500 uppercase font-bold">Verification Status</label>
                    <p class="{{ $user->is_verified ? 'text-green-400' : 'text-yellow-500' }} font-bold">
                        {{ $user->is_verified ? '● Verified' : '● Pending' }}
                    </p>
                </div>
            </div>

            <div class="pt-4">
                <label class="text-xs text-gray-500 uppercase font-bold block mb-2">Documents</label>
                <div class="flex gap-4">
                    @if($user->ktp_photo)
                    <a href="{{ asset('storage/' . $user->ktp_photo) }}" target="_blank" class="text-blue-400 hover:underline text-sm">📄 Lihat KTP</a>
                    @else
                    <span class="text-gray-500 text-sm">No KTP</span>
                    @endif

                    @if($user->certificate_photo)
                    <a href="{{ asset('storage/' . $user->certificate_photo) }}" target="_blank" class="text-blue-400 hover:underline text-sm">📜 Lihat Sertifikat</a>
                    @endif
                </div>
            </div>
            @endif

            <div class="pt-6 flex gap-3 border-t border-gray-700 mt-6">

                @if($user->email === 'admin@rumahberes.com')
                <button disabled class="flex-1 bg-gray-700 text-gray-500 cursor-not-allowed py-2 rounded-lg font-bold border border-gray-600">
                    🔒 Edit Locked
                </button>
                @else
                <a href="{{ route('admin.users.edit', $user->id) }}" class="flex-1 bg-yellow-600 hover:bg-yellow-500 text-white text-center py-2 rounded-lg font-bold transition shadow-lg">
                    ✏️ Edit User
                </a>
                @endif

                @if($user->email === 'admin@rumahberes.com' || $user->id === Auth::id())
                <button disabled class="flex-1 bg-gray-700 text-gray-500 cursor-not-allowed py-2 rounded-lg font-bold border border-gray-600">
                    🔒 Delete Locked
                </button>
                @else
                <button onclick="confirmDelete({{ $user->id }})" class="flex-1 bg-red-600 hover:bg-red-500 text-white py-2 rounded-lg font-bold transition shadow-lg">
                    🗑️ Delete User
                </button>

                <form id="delete-form-{{ $user->id }}" action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
                @endif

            </div>

        </div>
    </div>
</div>

<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: 'Yakin hapus user ini?',
            text: "Data akan hilang permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#374151',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            background: '#1f2937',
            color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + userId).submit();
            }
        })
    }
</script>
@endsection