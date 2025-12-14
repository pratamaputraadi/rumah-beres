@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-white mb-6">Edit My Profile</h2>

    <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-lg">

        <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                <div class="text-center">
                    <label class="block text-gray-400 text-sm font-bold mb-2">Profile Picture</label>

                    <div class="w-40 h-40 rounded-full overflow-hidden border-4 border-gray-700 mx-auto mb-4 bg-gray-900 flex items-center justify-center relative group">
                        <img id="avatar-preview"
                            src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                            class="w-full h-full object-cover {{ $user->avatar ? '' : 'p-4 opacity-50' }}">

                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer"
                            onclick="document.getElementById('avatar-input').click()">
                            <span class="text-white font-bold">Change Photo</span>
                        </div>
                    </div>

                    <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden" onchange="previewImage(event)">
                    <p class="text-xs text-gray-500 mt-2">Klik foto untuk mengubah. (JPG/PNG, Max 2MB)</p>

                    @error('avatar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>


                <div class="md:col-span-2 space-y-5">

                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                            class="w-full bg-gray-900 text-white rounded-lg p-3 border border-gray-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition" required>
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Email Address (Cannot be changed)</label>
                        <input type="email" value="{{ $user->email }}" disabled
                            class="w-full bg-gray-700 text-gray-400 rounded-lg p-3 border border-gray-600 cursor-not-allowed">
                    </div>

                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Role</label>
                        <div>
                            <span class="px-3 py-1 bg-blue-900 text-blue-300 rounded-full text-xs font-bold border border-blue-700 uppercase">
                                {{ $user->role }}
                            </span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-400 text-sm font-bold mb-2">Short Bio</label>
                        <textarea name="bio" rows="4" placeholder="Tulis sedikit tentang diri Anda..."
                            class="w-full bg-gray-900 text-white rounded-lg p-3 border border-gray-700 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">{{ old('bio', $user->bio) }}</textarea>
                        @error('bio') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter.</p>
                    </div>

                </div>
            </div>

            <div class="pt-6 border-t border-gray-700 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg flex items-center gap-2">
                    <span>💾</span> Save Changes
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    // Tampilkan notifikasi sukses jika ada session 'success'
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

    // Fungsi untuk preview gambar sebelum diupload
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('avatar-preview');
            output.src = reader.result;
            // Hapus class padding/opacity jika sebelumnya menggunakan default image
            output.classList.remove('p-4', 'opacity-50');
        }
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection