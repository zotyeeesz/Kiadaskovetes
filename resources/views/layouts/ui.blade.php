<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SpendWise')</title>
    @include('partials.ui_styles')
    @stack('head')
</head>
<body class="@yield('body_class', 'page-body')">
    @yield('content')

    @stack('scripts')
</body>
</html>
