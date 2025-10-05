<!DOCTYPE html>
<html lang="id">

<head>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initia       l-scale=1.0">
    <title>Aplikasi Surat Penawaran</title>

    @vite('resources/css/app.css')

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">

    <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <nav class="container mx-auto px-4 flex justify-between items-center py-3">
            <div class="flex items-center gap-8">
                <a href="{{ url('/') }}" class="font-bold text-lg tracking-wider">PENAWARAN.APP</a>
                <ul class="hidden lg:flex items-center gap-6">
                    <li><a href="{{ url('/') }}" class="text-xs uppercase font-bold text-black">Dashboard</a></li>
                    <li><a href="#" class="text-xs uppercase font-medium text-gray-500 hover:text-black">Arsip</a></li>
                </ul>
            </div>
            <div class="flex items-center gap-6">
                <a href="{{ route('penawaran.create') }}" class="bg-black text-white text-xs font-bold py-2 px-6 rounded hover:bg-gray-800 transition">
                    BUAT PENAWARAN
                </a>
                <span class="hidden lg:block font-medium text-sm">Username</span>
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
    </main>

    <footer class="bg-gray-900 py-8">
        <div class="container mx-auto px-4 flex flex-col items-center">
            <img src="{{ asset('images/logo-placeholder.png') }}" alt="Logo" class="h-10 mb-4">

            <p class="text-sm text-gray-400">
                Copyright ©2025 Penawaran.App All rights Reserved
            </p>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
</body>

</html>
</body>

</html>