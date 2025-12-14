@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-6xl mx-auto">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">User Management</h2>

        @if($isSuperAdmin && $selectedRole == 'admin')
        <button onclick="document.getElementById('addAdminModal').classList.remove('hidden')"
            class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-lg font-bold text-sm flex items-center gap-2 transition shadow-lg">
            <span>+</span> Add New Admin
        </button>
        @endif
    </div>

    <div class="flex flex-wrap gap-3 mb-4">

        @if($isSuperAdmin)
        <a href="{{ route('admin.users.index', ['role' => 'admin']) }}"
            class="px-4 py-2 rounded-full text-sm font-bold border transition 
           {{ $selectedRole == 'admin' ? 'bg-red-600 text-white border-red-600' : 'bg-gray-800 text-gray-400 border-gray-600 hover:bg-gray-700' }}">
            Admins
        </a>
        @endif

        <a href="{{ route('admin.users.index', ['role' => 'customer']) }}"
            class="px-4 py-2 rounded-full text-sm font-bold border transition 
           {{ $selectedRole == 'customer' ? 'bg-blue-600 text-white border-blue-600' : 'bg-gray-800 text-gray-400 border-gray-600 hover:bg-gray-700' }}">
            Customers
        </a>
        <a href="{{ route('admin.users.index', ['role' => 'technician']) }}"
            class="px-4 py-2 rounded-full text-sm font-bold border transition 
           {{ $selectedRole == 'technician' ? 'bg-purple-600 text-white border-purple-600' : 'bg-gray-800 text-gray-400 border-gray-600 hover:bg-gray-700' }}">
            Technicians
        </a>
    </div>

    @if($selectedRole == 'technician')
    <div class="flex flex-wrap gap-2 mb-8 ml-4">

        <a href="{{ route('admin.users.index', ['role' => 'technician']) }}"
            class="px-3 py-1 rounded-full text-xs font-semibold border transition 
           {{ empty($selectedSpecialization) ? 'bg-purple-900 text-purple-300 border-purple-700' : 'bg-gray-800 text-gray-500 border-gray-700 hover:bg-gray-700' }}">
            All Specializations
        </a>

        @foreach($specializations as $spec)
        <a href="{{ route('admin.users.index', ['role' => 'technician', 'specialization' => $spec]) }}"
            class="px-3 py-1 rounded-full text-xs font-semibold border transition 
           {{ (isset($selectedSpecialization) && $selectedSpecialization == $spec) ? 'bg-purple-900 text-purple-300 border-purple-700' : 'bg-gray-800 text-gray-500 border-gray-700 hover:bg-gray-700' }}">
            {{ $spec }}
        </a>
        @endforeach

    </div>
    @endif
    <div class="bg-gray-800 rounded-xl border border-gray-700 overflow-hidden shadow-lg">
        <table class="w-full text-left text-gray-400">
            <thead class="bg-gray-900 text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-3">User</th>
                    <th class="px-6 py-3">Role</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($users as $u)
                <tr class="hover:bg-gray-700/50 transition">
                    <td class="px-6 py-4 flex items-center gap-3">
                        <div class="h-10 w-10 rounded-full bg-gray-600 overflow-hidden border border-gray-500">
                            @if($u->avatar)
                            <img src="{{ asset('storage/' . $u->avatar) }}" class="h-full w-full object-cover">
                            @else
                            <div class="h-full w-full flex items-center justify-center font-bold text-white">{{ substr($u->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div>
                            <div class="font-bold text-white flex items-center gap-2">
                                {{ $u->name }}
                                @if($u->id == Auth::id()) <span class="text-[10px] bg-green-900 text-green-300 px-2 rounded-full border border-green-700">(You)</span> @endif
                            </div>
                            <div class="text-xs">{{ $u->email }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs font-bold uppercase 
                            {{ $u->role == 'admin' ? 'bg-red-900 text-red-300 border border-red-800' : ($u->role == 'technician' ? 'bg-purple-900 text-purple-300 border border-purple-800' : 'bg-blue-900 text-blue-300 border border-blue-800') }}">
                            {{ $u->role }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @if($u->role == 'technician')
                        @if($u->is_verified) <span class="text-green-400 text-xs font-bold">● Verified</span>
                        @else <span class="text-yellow-500 text-xs font-bold">● Pending</span> @endif
                        @else
                        <span class="text-gray-500 text-xs">-</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <a href="{{ route('admin.users.show', $u->id) }}" class="p-2 bg-gray-700 rounded hover:bg-gray-600 text-white transition shadow" title="View Detail">👁️</a>

                        @if(!$isSuperAdmin && $u->role == 'admin')
                        @else

                        @if($u->email === 'admin@rumahberes.com')
                        <button disabled class="p-2 bg-gray-700 rounded text-gray-500 cursor-not-allowed shadow" title="Edit Protected (Use My Profile)">🔒</button>
                        @else
                        <a href="{{ route('admin.users.edit', $u->id) }}" class="p-2 bg-yellow-700 rounded hover:bg-yellow-600 text-white transition shadow" title="Edit Data">✏️</a>
                        @endif

                        @if($u->email !== 'admin@rumahberes.com' && $u->id !== Auth::id())
                        <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST" id="delete-form-{{ $u->id }}">
                            @csrf @method('DELETE')
                            <button type="button" onclick="confirmDelete({{ $u->id }})" class="p-2 bg-red-700 rounded hover:bg-red-600 text-white transition shadow" title="Delete User">🗑️</button>
                        </form>
                        @else
                        <button disabled class="p-2 bg-gray-700 rounded text-gray-500 cursor-not-allowed shadow" title="Protected Account">🔒</button>
                        @endif

                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-8 text-center text-gray-500">Tidak ada user ditemukan dalam kategori ini.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($isSuperAdmin)
<div id="addAdminModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-80 flex items-center justify-center backdrop-blur-sm transition-opacity">
    <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 w-full max-w-md relative shadow-2xl transform scale-100 transition-transform">

        <button onclick="document.getElementById('addAdminModal').classList.add('hidden')" class="absolute top-4 right-4 text-gray-400 hover:text-white text-xl">✖</button>

        <div class="text-center mb-6">
            <div class="w-12 h-12 bg-blue-900 rounded-full flex items-center justify-center mx-auto mb-3 text-blue-300 text-xl font-bold">🛡️</div>
            <h3 class="text-xl font-bold text-white">Create New Admin</h3>
            <p class="text-xs text-gray-400">Admin baru akan memiliki akses penuh ke sistem.</p>
        </div>

        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Full Name</label>
                <input type="text" name="name" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none transition" required>
            </div>
            <div>
                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Email Address</label>
                <input type="email" name="email" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none transition" required>
            </div>
            <div>
                <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Password</label>
                <input type="password" name="password" class="w-full bg-gray-900 border border-gray-600 rounded-lg p-3 text-white focus:border-blue-500 outline-none transition" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 rounded-xl transition shadow-lg mt-4">
                Create Admin Account
            </button>
        </form>
    </div>
</div>
@endif

<script>
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: "{{ session('success') }}",
        background: '#1f2937',
        color: '#fff',
        timer: 3000,
        showConfirmButton: false
    });
    @endif

    function confirmDelete(userId) {
        Swal.fire({
            title: 'Yakin hapus user ini?',
            text: "Data tidak bisa kembali!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#374151',
            confirmButtonText: 'Ya, Hapus!',
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