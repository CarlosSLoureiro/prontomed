<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="description" content="">
    <meta name="author" content="Carlos Loureiro">

    <title>{{ config('app.name') }}</title>

    <link rel="shortcut icon" href="/favicon.svg" />

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body>
    <span class="app-page">
        @include('layouts.v1.navbar')

        <main class="container">
            @yield('content')
        </main>
    </span>

    @include('layouts.v1.login');

    @include('layouts.v1.modals')
    
  </body>
</html>