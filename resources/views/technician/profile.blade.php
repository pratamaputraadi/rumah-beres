@extends('layouts.main')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold text-white mb-6">My Professional Profile</h2>

    @if(!$user->is_verified)
    <div class="bg-yellow-900/50 border border-yellow-700 text-yellow-300 p-4 rounded-lg mb-6 flex items-center gap-3">
        <span class="text-xl">⚠️</span>
        <div>
            <p class="font-bold">STATUS: Pending / Verification Required</p>
            <p class="text-sm">Anda tidak dapat menerima Job Request sebelum akun Anda disetujui oleh Admin. Mohon lengkapi atau tunggu proses verifikasi.</p>
        </div>
    </div>
    @endif

    @if(session('success') && str_contains(session('success'), 'Verifikasi Ulang'))
    <div class="bg-red-900/50 border border-red-700 text-red-300 p-4 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @elseif(session('success'))
    <div class="bg-green-900/50 border border-green-700 text-green-300 p-4 rounded-lg mb-6">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-900/50 border border-red-700 text-red-300 p-4 rounded-lg mb-6 text-sm">
        <p class="font-bold mb-2">Error Saving Profile:</p>
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-gray-800 p-8 rounded-2xl border border-gray-700 shadow-lg">

        <form action="{{ route('technician.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="flex items-start gap-6 border-b border-gray-700 pb-6">
                <div class="text-center flex-shrink-0">
                    <label class="block text-gray-400 text-sm font-bold mb-2">Profile Photo</label>
                    <div class="w-24 h-24 rounded-full overflow-hidden border-3 border-gray-700 mx-auto mb-2 bg-gray-900 flex items-center justify-center relative group">
                        <img id="avatar-preview"
                            src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('images/default-avatar.png') }}"
                            class="w-full h-full object-cover {{ $user->avatar ? '' : 'p-3 opacity-50' }}">
                        <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition cursor-pointer"
                            onclick="document.getElementById('avatar-input').click()">
                            <span class="text-white text-xs">Change</span>
                        </div>
                    </div>
                    <input type="file" id="avatar-input" name="avatar" accept="image/*" class="hidden" onchange="previewImage(event)">
                </div>

                <div class="flex-grow space-y-3">
                    <div>
                        <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Full Name</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-gray-900 text-white rounded-lg p-2 border border-gray-700 outline-none" required>
                    </div>
                    <div>
                        <label class="block text-gray-400 text-xs font-bold uppercase mb-1">Bio</label>
                        <textarea name="bio" rows="2" placeholder="Tulis deskripsi singkat..."
                            class="w-full bg-gray-900 text-white rounded-lg p-2 border border-gray-700 outline-none">{{ old('bio', $user->bio) }}</textarea>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-bold text-white mt-6 mb-4">Verification & Work Details</h3>

            <div class="space-y-4">

                <div>
                    <label class="block text-gray-400 text-sm font-bold mb-1">Service Specialization</label>
                    <input type="text" value="{{ $user->specialization }}" class="w-full bg-gray-700 text-gray-400 rounded-lg p-3 border border-gray-600 cursor-not-allowed" disabled>
                    <p class="text-xs text-gray-500 mt-1">Specialization cannot be changed after registration.</p>
                </div>

                <div>
                    <label class="block text-gray-400 text-sm font-bold mb-1">Identity Card (KTP)</label>
                    <input type="file" name="ktp" accept="image/*" class="w-full bg-gray-900 rounded-lg p-2 border border-gray-700 text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                    <p class="text-xs text-gray-500 mt-1">Current file: {{ $user->ktp_photo ? basename($user->ktp_photo) : 'None' }}. Uploading new file requires re-verification.</p>
                </div>

                <div>
                    <label class="block text-gray-400 text-sm font-bold mb-1">Professional Certification (Optional)</label>
                    <input type="file" name="certificate" accept="image/*" class="w-full bg-gray-900 rounded-lg p-2 border border-gray-700 text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                    <p class="text-xs text-gray-500 mt-1">Current file: {{ $user->certificate_photo ? basename($user->certificate_photo) : 'None' }}. Uploading new file requires re-verification.</p>
                </div>

                <div>
                    <label class="block text-gray-400 text-sm font-bold mb-1">Home Address</label>
                    <textarea name="address" rows="2" class="w-full bg-gray-900 text-white rounded-lg p-3 border border-gray-700 outline-none">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="pt-4 border-t border-gray-700">
                    <label class="block text-gray-400 text-sm font-bold mb-1">Change Password (Optional)</label>
                    <input type="password" name="password" placeholder="Enter new password" class="w-full bg-gray-900 rounded-lg p-3 border border-gray-700 outline-none">
                </div>

            </div>

            <div class="pt-6 flex justify-end">
                <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white font-bold py-3 px-8 rounded-xl transition shadow-lg flex items-center gap-2">
                    <span>💾</span> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Fungsi untuk preview gambar sebelum diupload
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('avatar-preview');
            output.src = reader.result;
            output.classList.remove('p-3', 'opacity-50');
        }
        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    }
</script>
@endsection