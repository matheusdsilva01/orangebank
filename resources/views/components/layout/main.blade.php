<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{asset('/favicon.ico')}}" sizes="any">
    <link rel="apple-touch-icon" href="{{asset('/apple-touch-icon.png')}}">
    @vite('resources/css/app.css')
    <title>OrangeBank</title>
</head>
<body>
    <main class="h-full">
        {{ $slot  }}
    </main>
</body>
</html>
