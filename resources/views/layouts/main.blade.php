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

                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }} rounded-lg mb-1 transition">
                    <span class="mr-2">📊</span> Dashboard
                </a>

                <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-gray-300 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }} rounded-lg mb-1 transition">
                    <span class="mr-2">👥</span> User List
                </a>

                <a href="{{ route('admin.verification.list') }}" class="flex items-center px-4 py-2 text-gray-400 {{ request()->routeIs('admin.verification.*') ? 'bg-gray-700 text-white' : 'hover:bg-gray-700' }} rounded-lg transition">
                    <span class="mr-2">✅</span> Verifikasi Teknisi
                </a>

                <a href="{{ route('admin.profile') }}" class="flex items-center px-4 py-2 text-gray-300 {{ request()->routeIs('admin.profile') ? 'bg-gray-700' : 'hover:bg-gray-700' }} rounded-lg mt-4 border-t border-gray-700 pt-4 transition">
                    <span class="mr-2 text-xl">👤</span> My Profile
                </a>


                @elseif(Auth::user()->role == 'technician')
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase">Teknisi Menu</p>

                {{-- 1. Job Requests (INCOMING) --}}
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-gray-300 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }} rounded-lg transition">
                    <span class="mr-3">🔧</span> Job Requests
                </a>

                {{-- 2. REQUEST (ACCEPTED JOBS) --}}
                <a href="{{ route('technician.accepted_jobs') }}" class="flex items-center px-4 py-2 text-gray-300 {{ request()->routeIs('technician.accepted_jobs') ? 'bg-gray-700' : 'hover:bg-gray-700' }} rounded-lg transition">
                    <span class="mr-3">📅</span> Request
                </a>

                <a href="{{ route('technician.profile') }}" class="flex items-center px-4 py-2 text-gray-300 {{ request()->routeIs('technician.profile') ? 'bg-gray-700' : 'hover:bg-gray-700' }} rounded-lg transition">
                    <span class="mr-3">👤</span> My Profile
                </a>


                @else
                <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Customer Menu</p>

                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 mb-1 text-gray-300 rounded-lg hover:bg-gray-700 transition">
                    <span class="mr-3">🏠</span> Home
                </a>

                <a href="{{ route('customer.orders') }}" class="flex items-center px-4 py-2 mb-1 text-gray-300 rounded-lg hover:bg-gray-700 transition">
                    <span class="mr-3">📋</span> Orders
                </a>

                <a href="{{ route('customer.categories') }}" class="flex items-center px-4 py-2 mb-1 text-gray-300 rounded-lg hover:bg-gray-700 transition">
                    <span class="mr-3">🔍</span> Categories
                </a>

                <a href="{{ route('customer.profile') }}" class="flex items-center px-4 py-2 mb-1 text-gray-300 rounded-lg hover:bg-gray-700 transition">
                    <span class="mr-3">👤</span> Profile
                </a>
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
                <div class="flex items-center gap-4">

                    <div class="h-10 w-10 rounded-full overflow-hidden border border-gray-600">
                        @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Avatar" class="w-full h-full object-cover">
                        @else
                        <div class="h-full w-full bg-blue-600 flex items-center justify-center text-sm font-bold">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        @endif
                    </div>

                    <span class="text-gray-400">Selamat Datang, <strong class="text-white">{{ Auth::user()->name }}</strong></span>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-900 p-6">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>