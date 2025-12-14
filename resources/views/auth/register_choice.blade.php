<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Rumah Beres</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-[#0f0f0f] flex items-center justify-center h-screen text-white">

    <div class="w-full max-w-md p-8 text-center">
        <div class="flex justify-center mb-8 gap-2">
            <div class="w-8 h-8 bg-white rounded flex items-center justify-center text-black font-bold">🛠️</div>
            <h1 class="text-2xl font-bold">Rumah Beres</h1>
        </div>

        <h2 class="text-xl font-semibold mb-8">Create An Account</h2>

        <div class="space-y-4">
            <a href="{{ route('register.customer') }}" class="block w-full py-4 border border-white rounded-full hover:bg-white hover:text-black transition font-bold text-lg">
                as Customer
            </a>

            <a href="{{ route('register.technician') }}" class="block w-full py-4 border border-white rounded-full hover:bg-white hover:text-black transition font-bold text-lg">
                as Technician
            </a>
        </div>

        <div class="mt-8 text-gray-400">
            Already have an account? <a href="{{ route('login') }}" class="text-white font-bold hover:underline">Sign In</a>
        </div>
    </div>

</body>

</html>