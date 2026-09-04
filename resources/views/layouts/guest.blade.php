<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login') - POS Raula</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" type="image/jpg" href="{{ asset('imagelogo/lopos.jpg') }}">


</head>

<body class="bg-gray-50 font-sans antialiased">
    @yield('content')
</body>

</html>
