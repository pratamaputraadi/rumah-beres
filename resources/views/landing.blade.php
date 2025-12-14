<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rumah Beres - Servis Tanpa Drama</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-[#0f0f0f] text-white">

    <nav class="flex justify-between items-center py-6 px-8 max-w-7xl mx-auto">
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center text-black font-bold text-xl">
                🛠️
            </div>
            <span class="font-bold text-xl tracking-tight">Rumah Beres</span>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('login') }}" class="px-6 py-2 rounded-lg border border-gray-600 hover:bg-gray-800 transition font-semibold">
                Login
            </a>

            <a href="{{ route('register.choice') }}" class="px-6 py-2 rounded-lg bg-white text-black hover:bg-gray-200 transition font-bold">
                Sign Up
            </a>
        </div>
    </nav>

    <header class="max-w-7xl mx-auto px-8 py-16 flex flex-col md:flex-row items-center gap-12">
        <div class="md:w-1/2 space-y-8">
            <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
                Wujudkan Servis <br>
                Perabotan <span class="text-gray-500">Tanpa <br> Drama!</span>
            </h1>
            <p class="text-lg text-gray-400 border-l-4 border-white pl-4">
                Satu langkah lagi menuju <br> perabotan yang terawat.
            </p>
            <div class="pt-4">
                <div class="h-1 w-32 bg-gray-700 rounded-full"></div>
            </div>
        </div>

        <div class="md:w-1/2 flex justify-center relative">
            <div class="relative z-10">
                <div class="w-80 h-80 bg-gradient-to-tr from-gray-700 to-gray-600 rounded-full flex items-center justify-center shadow-2xl animate-pulse">
                    <span class="text-6xl">🔧 ⚙️</span>
                </div>
            </div>
            <div class="absolute top-10 right-10 w-72 h-72 bg-blue-500/20 rounded-full blur-3xl"></div>
        </div>
    </header>

    <section class="max-w-7xl mx-auto px-8 py-16">
        <div class="border border-gray-800 rounded-3xl p-10 bg-[#161616] relative overflow-hidden">
            <h2 class="text-3xl font-bold mb-8">Mengapa Harus <br> Rumah Beres?</h2>

            <div class="grid md:grid-cols-1 gap-6 text-gray-300">
                <div class="flex items-start gap-4">
                    <span class="text-white font-bold">•</span>
                    <p><strong class="text-white">AI Diagnostic & Transparansi:</strong> "Ketahui estimasi biaya di awal. Tidak ada mark-up harga tiba-tiba."</p>
                </div>
                <div class="flex items-start gap-4">
                    <span class="text-white font-bold">•</span>
                    <p><strong class="text-white">Teknisi Terverifikasi:</strong> "Semua teknisi bersertifikat dan telah melalui proses verifikasi ketat."</p>
                </div>
                <div class="flex items-start gap-4">
                    <span class="text-white font-bold">•</span>
                    <p><strong class="text-white">Layanan Fleksibel:</strong> "Pilih solusi sesuai budget: AI Check, Video Call, atau Kunjungan Langsung."</p>
                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-8 mb-20">
        <h3 class="text-2xl font-bold mb-6">Social Proof</h3>
        <div class="grid md:grid-cols-2 gap-6">
            <div class="bg-[#1a1a1a] p-6 rounded-2xl border border-gray-800">
                <p class="text-gray-400 text-sm italic mb-4">
                    "Awalnya takut dipatok harga mahal servis AC, tapi pakai Rumah Beres estimasinya pas dan transparan. Teknisi sangat sopan!"
                </p>
                <p class="font-bold text-white">- Erizka, Bekasi</p>
            </div>
            <div class="bg-[#1a1a1a] p-6 rounded-2xl border border-gray-800">
                <p class="text-gray-400 text-sm italic mb-4">
                    "Servis kulkas jadi gampang banget. Tinggal video call diagnosis, teknisi datang bawa alat yang pas. Hemat waktu!"
                </p>
                <p class="font-bold text-white">- Gilang, Purwakarta</p>
            </div>
        </div>
    </section>

    <footer class="max-w-7xl mx-auto px-8 pb-16 text-center">
        <div class="border border-white/20 rounded-full py-4 px-8 inline-block hover:bg-white/5 transition cursor-pointer">
            <p class="font-bold">Download The <span class="text-white">Rumah Beres</span> <br> <span class="text-gray-400 font-normal">Official App Only On Store</span></p>
        </div>
        <div class="mt-12 text-gray-600 text-sm">
            &copy; 2025 Rumah Beres. All rights reserved.
        </div>
    </footer>

</body>

</html>