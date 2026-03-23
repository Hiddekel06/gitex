<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'GITEX')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background: #111; color: #fff; min-height: 100vh; display: flex; flex-direction: column; min-height: 100vh;">
    @include('partials.header')
    <main style="flex:1 0 auto;">
        @yield('content')
    </main>
    @include('partials.footer')
</body>
</html>
