<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rumah Beres</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 flex items-center justify-center h-screen text-white">

    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-96 border border-gray-700">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-white">Rumah Beres</h1>
            <p class="text-gray-400 text-sm">Masuk untuk melanjutkan</p>
        </div>

        @if(session('success'))
        <div class="bg-green-500/20 border border-green-500 text-green-400 p-3 rounded-lg mb-4 text-sm text-center">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="bg-red-500/20 border border-red-500 text-red-400 p-2 rounded mb-4 text-sm text-center">
            {{ $errors->first() }}
        </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm mb-2">Email Address</label>
                <input type="email" name="email" class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-blue-500" placeholder="admin@rumahberes.com" required>
            </div>

            <div class="mb-6">
                <label class="block text-sm mb-2">Password</label>
                <input type="password" name="password" class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-blue-500" placeholder="password" required>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-2 rounded transition">
                Sign In
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-400">
            Don't Have an Account?
            <a href="{{ route('register.choice') }}" class="text-white font-bold hover:underline">
                Sign Up
            </a>
        </div>

    </div>

</body>

</html>