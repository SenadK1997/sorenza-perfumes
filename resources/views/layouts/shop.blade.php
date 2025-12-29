<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sorenza')</title>
    @vite('resources/css/app.css')
</head>
<body 
    {{-- class="!bg-gradient-to-b !from-white !to-black text-gray-800 font-sans" --}}
    >

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <a href="/" class="text-2xl font-bold text-gray-900">Sorenza</a>
                <nav class="space-x-4">
                    <a href="/" class="text-gray-700 hover:text-gray-900">Home</a>
                    <a href="/shop" class="text-gray-700 hover:text-gray-900">Shop</a>
                    {{-- <a href="#" class="text-gray-700 hover:text-gray-900">About</a> --}}
                    {{-- <a href="#" class="text-gray-700 hover:text-gray-900">Contact</a> --}}
                </nav>
            </div>
        </div>
    </header>

    <!-- Main content -->
    <main class="py-12">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-6 mt-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            &copy; {{ date('Y') }} Sorenza. All rights reserved.
        </div>
    </footer>
<script type="module" src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1/dist/index.min.js"></script>
</body>
</html>
