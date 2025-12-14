<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Customer</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#0f0f0f] flex items-center justify-center min-h-screen text-white py-10">

    <div class="w-full max-w-md p-8">
        <div class="mb-8">
            <h1 class="text-2xl font-bold mb-2">Create An Account - Customer</h1>
            <p class="text-gray-400 text-sm">Get started in seconds. Unlock exclusive features.</p>
        </div>

        <form action="{{ route('register.customer.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-gray-400 text-sm mb-1">Full Name</label>
                <input type="text" name="name" class="w-full bg-[#1a1a1a] rounded-lg p-3 border border-gray-700 focus:border-white outline-none" required>
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-1">Email</label>
                <input type="email" name="email" class="w-full bg-[#1a1a1a] rounded-lg p-3 border border-gray-700 focus:border-white outline-none" required>
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-1">Password</label>
                <input type="password" name="password" class="w-full bg-[#1a1a1a] rounded-lg p-3 border border-gray-700 focus:border-white outline-none" required>
            </div>

            <div>
                <label class="block text-gray-400 text-sm mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" class="w-full bg-[#1a1a1a] rounded-lg p-3 border border-gray-700 focus:border-white outline-none" required>
            </div>

            <button type="submit" class="w-full bg-white text-black font-bold py-3 rounded-full mt-4 hover:bg-gray-200 transition">
                Sign Up
            </button>
        </form>

        <p class="text-center text-gray-400 text-sm mt-6">
            Already Have an Account? <a href="{{ route('login') }}" class="text-white font-bold">Sign In</a>
        </p>
    </div>

</body>

</html>