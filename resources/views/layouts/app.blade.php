<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Penawaran</title>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.css" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Style untuk Sidebar Submenu */
        .submenu {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .submenu.open {
            max-height: 500px;
        }
        .rotate-icon {
            transform: rotate(180deg);
        }
        /* Fix agar dropdown tabel tidak terpotong */
        .table-responsive {
            overflow-x: auto;
            min-height: 300px; /* Memberi ruang untuk dropdown */
        }
    </style>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal">

    <div class="flex h-screen overflow-hidden">

        <aside class="w-64 bg-white border-r border-gray-200 hidden md:flex md:flex-col flex-shrink-0 z-30">

            <div class="p-6 flex items-center justify-center border-b border-gray-100">
                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-800 flex items-center gap-2">
                    <span class="bg-blue-100 text-blue-600 p-2 rounded-lg">🚀</span>
                    <span>PENAWARAN.APP</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-2">

                <a href="{{ route('harga.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors group">
                    <span class="mr-3 text-gray-400 group-hover:text-blue-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </span>
                    <span class="font-medium">Harga Produk</span>
                </a>

                <div x-data="{ open: false }">
                    <button @click="open = !open" onclick="toggleSubmenu('penawaran-submenu', 'penawaran-icon')" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors group focus:outline-none">
                        <div class="flex items-center">
                            <span class="mr-3 text-gray-400 group-hover:text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </span>
                            <span class="font-medium">Buat Penawaran</span>
                        </div>
                        <svg id="penawaran-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="penawaran-submenu" class="submenu pl-11 pr-2 space-y-1">
                        <a href="{{ route('penawaran.create_combined') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Penawaran Proyek</a>
                        <a href="{{ route('penawaran.create_product') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Penawaran Produk</a>
                    </div>
                </div>

                <div>
                    <button onclick="toggleSubmenu('histori-submenu', 'histori-icon')" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-blue-50 hover:text-blue-700 rounded-lg transition-colors group focus:outline-none">
                        <div class="flex items-center">
                            <span class="mr-3 text-gray-400 group-hover:text-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <span class="font-medium">Histori</span>
                        </div>
                        <svg id="histori-icon" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div id="histori-submenu" class="submenu pl-11 pr-2 space-y-1">
                        <a href="{{ route('histori.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Histori Penawaran</a>
                        <a href="{{ route('invoice.histori') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Histori Invoice</a>
                        <a href="{{ route('bast.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Histori BAST</a>
                        <a href="{{ route('skp.index') }}" class="block px-4 py-2 text-sm text-gray-600 hover:text-blue-600 hover:bg-gray-50 rounded-md">Histori SPK</a>
                    </div>
                </div>

            </nav>

            <div class="p-4 border-t border-gray-100">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xs">
                            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="text-sm">
                            <p class="font-medium text-gray-700 w-24 truncate">{{ Auth::user()->name ?? 'Guest' }}</p>
                            <p class="text-xs text-gray-500">Admin</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Logout">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col w-full h-screen overflow-hidden">

            <header class="bg-white shadow-sm z-20 h-16 flex items-center justify-between px-6">
                <h2 class="text-xl font-bold text-gray-800">
                    Welcome, <span class="text-blue-600">{{ Auth::user()->name ?? 'Admin' }}</span> 👋
                </h2>
                <button class="md:hidden text-gray-500 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </header>

            <main class="flex-1 overflow-x-auto overflow-y-auto bg-gray-50 p-6 z-10 relative">
                @yield('content')
            </main>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.1/flowbite.min.js"></script>

    <script>
        // Fungsi Sidebar Dropdown
        function toggleSubmenu(submenuId, iconId) {
            const submenu = document.getElementById(submenuId);
            const icon = document.getElementById(iconId);

            if (submenu.classList.contains('open')) {
                submenu.classList.remove('open');
                icon.classList.remove('rotate-180');
            } else {
                submenu.classList.add('open');
                icon.classList.add('rotate-180');
            }
        }

        // Fungsi Manual untuk Dropdown Tabel (Jaga-jaga jika Alpine/Flowbite gagal)
        // Gunakan di view tabel Anda: <button onclick="toggleDropdown('dropdown-row-1')">
        function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);

            // Tutup semua dropdown lain dulu
            document.querySelectorAll('.table-dropdown-menu').forEach(el => {
                if(el.id !== dropdownId) el.classList.add('hidden');
            });

            // Toggle dropdown target
            if (dropdown) {
                dropdown.classList.toggle('hidden');
            }
        }

        // Tutup dropdown jika klik di luar area
        document.addEventListener('click', function(event) {
            // Cek apakah yang diklik adalah tombol dropdown atau menu itu sendiri
            const isDropdownButton = event.target.closest('[onclick^="toggleDropdown"]');
            const isDropdownMenu = event.target.closest('.table-dropdown-menu');

            if (!isDropdownButton && !isDropdownMenu) {
                document.querySelectorAll('.table-dropdown-menu').forEach(el => {
                    el.classList.add('hidden');
                });
            }
        });
    </script>
</body>
</html>