@extends('layouts.main')

@section('content')
<div class="max-w-4xl mx-auto pb-10">

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white">Welcome Home! {{ $user->name }}</h1>
        <p class="text-gray-400 text-sm mt-1">{{ $user->bio ?? ($user->address ?? 'Lengkapi profil Anda.') }}</p>
    </div>

    @if(session('success'))
    <div class="bg-green-500/20 text-green-400 p-4 rounded-lg mb-6 border border-green-500/50">
        ✅ {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-500/20 text-red-400 p-4 rounded-lg mb-6 border border-red-500/50 text-sm">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="flex flex-col md:flex-row gap-8">

            <div class="flex-shrink-0 flex flex-col items-center space-y-4">
                <div class="w-40 h-40 bg-gray-800 rounded-2xl overflow-hidden border border-gray-700 relative group">
                    @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover" id="avatarPreview">
                    @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=374151&color=fff&size=256" class="w-full h-full object-cover" id="avatarPreview">
                    @endif
                </div>

                <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*" onchange="previewImage(event)">
                <label for="avatarInput" class="w-full py-2 px-4 rounded-lg border border-gray-600 text-gray-300 text-sm hover:bg-gray-800 transition text-center cursor-pointer">
                    Choose Photo
                </label>
            </div>

            <div class="flex-grow space-y-5">

                <div>
                    <label class="block text-gray-400 text-xs mb-1 ml-1 font-semibold">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-[#2A2A2A] text-white rounded-xl px-4 py-3 border border-transparent focus:border-gray-500 focus:bg-[#333] outline-none">
                </div>

                <div>
                    <label class="block text-gray-400 text-xs mb-1 ml-1 font-semibold">Bio</label>
                    <input type="text" name="bio" value="{{ old('bio', $user->bio) }}" placeholder="Misal: Penggemar No.1 Rumah Beres" class="w-full bg-[#2A2A2A] text-white rounded-xl px-4 py-3 border border-transparent focus:border-gray-500 focus:bg-[#333] outline-none">
                </div>

                <div>
                    <label class="block text-gray-400 text-xs mb-1 ml-1 font-semibold">Address</label>
                    <textarea name="address" rows="2" class="w-full bg-[#2A2A2A] text-white rounded-xl px-4 py-3 border border-transparent focus:border-gray-500 focus:bg-[#333] outline-none">{{ old('address', $user->address) }}</textarea>
                </div>

                <div class="pt-4 border-t border-gray-800">
                    <p class="text-gray-500 text-xs mb-4 italic">*Kosongkan jika tidak ingin mengganti password.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-gray-400 text-xs mb-1 ml-1 font-semibold">New Password</label>
                            <input type="password" name="password" class="w-full bg-[#2A2A2A] text-white rounded-xl px-4 py-3 border border-transparent focus:border-gray-500 focus:bg-[#333] outline-none">
                        </div>
                        <div>
                            <label class="block text-gray-400 text-xs mb-1 ml-1 font-semibold">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="w-full bg-[#2A2A2A] text-white rounded-xl px-4 py-3 border border-transparent focus:border-gray-500 focus:bg-[#333] outline-none">
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-800">
                    <label class="block text-gray-400 text-xs mb-1 ml-1 font-semibold">ID (KTP)</label>
                    <input type="file" name="ktp_photo" class="w-full bg-[#2A2A2A] text-gray-400 rounded-xl px-4 py-3 border border-transparent file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                    @if($user->ktp_photo)
                    <p class="text-xs text-green-500 mt-2">✓ KTP sudah tersimpan. Upload lagi jika ingin mengganti.</p>
                    @endif
                </div>

            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-gray-800">
            <button type="submit" class="w-full bg-white text-black font-bold py-3 rounded-xl hover:bg-gray-200 transition text-center shadow-lg">
                Save Changes
            </button>
        </div>
    </form>

    <div class="mt-4">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full border border-gray-600 text-white font-bold py-3 rounded-xl hover:bg-gray-800 transition text-center">
                Logout
            </button>
        </form>
    </div>

</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('avatarPreview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection