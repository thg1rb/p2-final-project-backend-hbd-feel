<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-thai text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        {{--            <div> --}}
        {{--                <a href="/"> --}}
        {{--                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" /> --}}
        {{--                </a> --}}
        {{--            </div> --}}
        <div class="bg-[#02a96b] rounded-xl p-3 m-2">
            <x-icon name="badge" size="48" class="stroke-white"></x-icon>
        </div>
        <div class="m-2 flex flex-col justify-center items-center">
            <p class="text-[36px] text-primary font-bold">ระบบนิสิตดีเด่น</p>
            <p>เข้าสู่ระบบเพื่อสมัครหรือพิจารณานิสิตดีเด่น</p>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
