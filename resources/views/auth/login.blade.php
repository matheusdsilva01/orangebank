<x-layout.main>
    <div class="h-full flex items-center justify-center p-2">
        <section class="w-full max-w-md">
            <h1 class="text-3xl font-bold mb-8 text-center">OrangeBank</h1>
            <div
                class="bg-purple-100 flex flex-col gap-6 rounded-xl border py-6 shadow-xl">
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
                            <input class="w-full rounded-md border px-3 py-1.5 shadow-xs" id="identifier" name="identifier"
                                   placeholder="Digite seu e-mail ou CPF" type="text">
                        </div>
                        <div>
                            <label class="text-sm font-medium mb-2 block" for="password">Senha</label>
                            <input class="w-full rounded-md border px-3 py-1.5 shadow-xs" id="password" name="password"
                                   placeholder="Digite sua senha" type="password">
                        </div>
                        <button type="submit"
                                class="bg-purple-600 hover:bg-purple-500 text-gray-50 border border-white rounded-md w-full font-medium transition-all px-4 py-2 text-sm cursor-pointer">
                            Entrar
                        </button>
                    </form>
                    <div class="text-center">
                        <button type="submit"
                                class="bg-purple-300 hover:bg-purple-200 border border-white rounded-md font-medium transition-all px-4 py-2 text-sm cursor-pointer">
                            Esqueceu sua senha?
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-layout.main>
