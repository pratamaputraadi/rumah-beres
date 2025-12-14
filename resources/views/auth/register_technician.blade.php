<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Technician</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#0f0f0f] flex items-center justify-center min-h-screen text-white py-10">

    <div class="w-full max-w-lg p-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold mb-2">Create An Account - Technician</h1>
            <p class="text-gray-400 text-sm">Join as a pro. Verification required.</p>
        </div>

        @if ($errors->any())
        <div class="bg-red-500/20 border border-red-500 text-red-400 p-4 rounded-lg mb-6 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('register.technician.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf

            <div>
                <label class="block text-gray-400 text-sm mb-1">Full Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="w-full bg-[#1a1a1a] rounded-lg p-3 border border-gray-700 focus:border-white outline-none" required>
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="w-full bg-[#1a1a1a] rounded-lg p-3 border border-gray-700 focus:border-white outline-none" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Password</label>
                    <input type="password" name="password" class="w-full bg-[#1a1a1a] rounded-lg p-3 border border-gray-700 focus:border-white outline-none" required>
                </div>
                <div>
                    <label class="block text-gray-400 text-sm mb-1">Confirm</label>
                    <input type="password" name="password_confirmation" class="w-full bg-[#1a1a1a] rounded-lg p-3 border border-gray-700 focus:border-white outline-none" required>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-800">
                <h3 class="font-bold text-lg mb-4">Verification Details</h3>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-1">Service Specialization</label>
                    <select name="specialization" class="w-full bg-[#1a1a1a] rounded-lg p-3 border border-gray-700 text-white focus:border-white outline-none">
                        <option value="Cooling">Cooling & Air Treatment</option>
                        <option value="Entertainment">Entertainment Appliances</option>
                        <option value="Laundry">Laundry Appliances</option>
                        <option value="Kitchen">Kitchen Appliances</option>
                        <option value="Computing">Computing & Home Office</option>
                        <option value="Water">Water Pumps & Heaters</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-1">Upload Identity Card (KTP)</label>
                    <input type="file" name="ktp" accept="image/*" class="w-full bg-[#1a1a1a] rounded-lg p-2 border border-gray-700 text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                    <p class="text-xs text-gray-500 mt-1">*Format: JPG/PNG, Maks 2MB</p>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-1">Professional Certificate (Optional)</label>
                    <input type="file" name="certificate" accept="image/*" class="w-full bg-[#1a1a1a] rounded-lg p-2 border border-gray-700 text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600">
                    <p class="text-xs text-gray-500 mt-1">*Jika punya sertifikat keahlian, peluang disetujui lebih besar.</p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-400 text-sm mb-1">Address</label>
                    <textarea name="address" rows="2" class="w-full bg-[#1a1a1a] rounded-lg p-3 border border-gray-700 focus:border-white outline-none">{{ old('address') }}</textarea>
                </div>
            </div>

            <button type="submit" class="w-full bg-white text-black font-bold py-3 rounded-full hover:bg-gray-200 transition">
                Submit for Verification
            </button>
        </form>

        <p class="text-center text-gray-400 text-sm mt-6">
            Already Have an Account? <a href="{{ route('login') }}" class="text-white font-bold">Sign In</a>
        </p>
    </div>

</body>

</html>