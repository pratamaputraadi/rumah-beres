<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rumah Beres - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-900 text-white">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-gray-800 border-r border-gray-700 hidden md:flex flex-col">
            <div class="h-16 flex items-center px-6 border-b border-gray-700">
                <h1 class="text-xl font-bold text-white tracking-wider">Rumah Beres</h1>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">
                @if(Auth::user()->role == 'admin')
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase">Admin Menu</p>
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 bg-gray-700 rounded-lg">Dashboard</a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-700 rounded-lg">Verifikasi Teknisi</a>

                @elseif(Auth::user()->role == 'technician')
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase">Teknisi Menu</p>
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 bg-gray-700 rounded-lg">Job Requests</a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-700 rounded-lg">Jadwal Saya</a>

                @else
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase">Customer Menu</p>
                <a href="#" class="flex items-center px-4 py-2 text-gray-300 bg-gray-700 rounded-lg">Home</a>
                <a href="#" class="flex items-center px-4 py-2 text-gray-400 hover:bg-gray-700 rounded-lg">Pesanan Saya</a>
                @endif
            </nav>

            <div class="p-4 border-t border-gray-700">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center px-4 py-2 text-red-400 hover:bg-gray-700 rounded-lg transition">
                        Keluar / Logout
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="h-16 bg-gray-800 border-b border-gray-700 flex items-center justify-between px-6">
                <span class="text-gray-400">Selamat Datang, <strong class="text-white">{{ Auth::user()->name }}</strong></span>
                <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center text-sm font-bold">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-900 p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>