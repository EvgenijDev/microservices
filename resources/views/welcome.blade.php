<!DOCTYPE html>
<html>
<head>
    @production
        <link href="{{ asset('build/assets/' . \Illuminate\Support\Facades\Vite::manifest()['resources/js/app.js']['file']) }}" rel="stylesheet">
        <script src="{{ asset('build/assets/' . \Illuminate\Support\Facades\Vite::manifest()['resources/js/app.js']['file']) }}" defer></script>
    @else
        @vite(['resources/js/app.js'])
    @endproduction
</head>
<body>
    <div id="app"></div>
</body>
</html>