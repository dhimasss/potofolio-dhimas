@props([
    'title' => null,
    'description' => null,
    'image' => null, // gambar pratinjau saat link dibagikan (WhatsApp, LinkedIn, dll.)
])

{{-- Kerangka HTML dasar: dipakai layout publik, layout admin, dan halaman login. --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ? $title.' — ' : '' }}{{ config('app.name') }}</title>

    @if ($description)
        <meta name="description" content="{{ $description }}">
        <meta property="og:description" content="{{ $description }}">
    @endif
    <meta property="og:title" content="{{ $title ?? config('app.name') }}">
    @if ($image)
        <meta property="og:image" content="{{ $image }}">
    @endif

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=fraunces:400,400i,600,700|instrument-sans:400,500,600" rel="stylesheet">

    {{-- Menandai bahwa JS aktif, agar animasi scroll-reveal hanya berlaku bila JS jalan --}}
    <script>document.documentElement.classList.add('js')</script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body {{ $attributes->merge(['class' => 'min-h-screen bg-paper font-sans text-ink antialiased']) }}>
    {{ $slot }}
</body>
</html>
