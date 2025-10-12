<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Dinas Kesehatan Kabupaten Tasikmalaya</title>

    {{-- Breeze/Jetstream usually uses Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen flex flex-col items-center bg-gray-100">

            {{-- === HEADER DI ATAS CARD === --}}
    <div class="w-full flex flex-col items-center pt-12 sm:pt-16">
        <img src="{{ asset('image/logo-dinkes.png') }}"
            alt="Logo"
            class="w-40 h-40 object-contain opacity-100">

        <h1 class="mt-6 text5xl sm:text-6xl font-extrabold text-gray-800 text-center tracking-wide leading-tight" style="font-size: 2rem">
            DINAS KESEHATAN KABUPATEN TASIKMALAYA
        </h1>

        <p class="mt-3 text-lg sm:text-xl text-gray-600 text-center font-medium">
            Silakan masuk untuk mengelola data layanan kesehatan
        </p>
    </div>


        <div class="w-full sm:max-w-md mt-6 sm:mt-8 px-6 py-6 bg-white shadow-md sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
