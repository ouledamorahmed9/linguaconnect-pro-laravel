<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- The @yield directive allows each page to set its own title --}}
    <title>@yield('title', 'وصلة تعليم')</title>

    <!-- Fonts & Styles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- This allows pages to add their own custom styles if needed --}}
    @stack('styles')
</head>
<body class="antialiased" style="font-family: 'Cairo', sans-serif;">
    <div class="bg-white">
        
        {{-- Here we include our reusable header --}}
        @include('layouts.partials._header')

        <main>
            {{-- This is where the unique content of each page will be injected --}}
            @yield('content')
        </main>
        
        {{-- Here we include our reusable footer --}}
        @include('layouts.partials._footer')

    </div>
</body>
</html>
