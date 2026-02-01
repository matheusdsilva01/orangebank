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
    <div class="h-full flex items-center justify-center p-2">
        <section class="w-full max-w-md">
            <h1 class="text-3xl font-bold mb-8 text-center">OrangeBank</h1>
            <div
                class="bg-fuchsia-100 flex flex-col gap-6 rounded-xl border py-6 shadow-xl">
                <div
                    class="px-6 space-y-1">
                    <h2 class="text-2xl font-bold text-center">Entrar</h2>
                    <p class="text-sm text-center">
                        Digite suas credenciais para acessar sua conta
                    </p>
                </div>
                <div class="px-6 space-y-6">
                    <form class="flex flex-col gap-6" method="POST" action="{{ route('login.authenticate') }}">
                        {{@csrf_field()}}
                        <div>
                            <label class="text-sm font-medium mb-2 block" for="identifier">E-mail ou CPF</label>
                            <input class="w-full rounded-md border px-3 py-1.5 shadow-xs" id="identifier"
                                   name="identifier"
                                   value="user@mail.com"
                                   placeholder="Digite seu e-mail ou CPF" type="text">
                            @error('identifier')
                            <p class="text-red-700 text-sm">{{$message}}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-sm font-medium mb-2 block" for="password">Senha</label>
                            <input value="password" class="w-full rounded-md border px-3 py-1.5 shadow-xs" id="password"
                                   name="password"
                                   placeholder="Digite sua senha" type="password">
                            @error('password')
                            <p class="text-red-700 text-sm">{{$message}}</p>
                            @enderror
                        </div>
                        <button type="submit"
                                class="bg-fuchsia-600 hover:bg-fuchsia-500 text-gray-50 border border-white rounded-md w-full font-medium transition-all px-4 py-2 text-sm cursor-pointer">
                            Entrar
                        </button>
                    </form>
                    <div class="text-center">
                        <button type="submit"
                                class="bg-fuchsia-300 hover:bg-fuchsia-200 border border-white rounded-md font-medium transition-all px-4 py-2 text-sm cursor-pointer">
                            Esqueceu sua senha?
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
</body>
</html>
