@props(
    [
        'title' => null,
        'backTo' => null
    ]
)
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('/favicon.ico')}}" sizes="any">
    <link rel="apple-touch-icon" href="{{asset('/apple-touch-icon.png')}}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>OrangeBank</title>
</head>
<body>
    <x-layout.header />
    <main class="h-full max-w-7xl mx-auto px-2 py-4">
        {{ $slot  }}
    </main>
</body>
</html>
