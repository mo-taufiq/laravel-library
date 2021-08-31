<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{ asset('css/default.css') }}">

    @include('partials.partial_header')
    @yield('header')
    <title>{{ env('APP_NAME') }} @yield('title', '')</title>
</head>

<body>
    @yield('content')

    @include('partials.partial_footer')
    @yield('footer')
</body>

</html>