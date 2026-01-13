@php use App\Support\MoneyHelper; @endphp
<x-layout.main title="Depósito" :back-to="route('dashboard')">
    <section class="max-w-lg mx-auto space-y-8">
        <div class="bg-fuchsia-200 p-6 flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
            <div class="flex items-center justify-between ">
                <h3 class="text-sm font-medium">Saldo atual - Conta Corrente</h3>
                <x-eos-account-balance-wallet-o class="size-5"/>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-primary">
                    {{MoneyHelper::format($currentAccount->balance)}}
                </h2>
                <p class="text-xs">Disponível em conta</p></div>
        </div>
        <div class="bg-fuchsia-200 p-6 flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
            <div>
                <h3 class="text-sm font-medium">Realizar depósito</h3>
                <p>Digite o valor que deseja depositar em sua conta corrente</p>
            </div>
            <form method="POST" action="{{route('account.deposit')}}" class="space-y-4">
                {{@csrf_field()}}
                <div class="space-y-2">
                    <label
                        class="text-sm"
                        for="amount"
                    >
                        Valor do depósito
                    </label>
                    <div class="relative">
                        <x-carbon-currency-dollar class="absolute left-3 top-1/2 -translate-y-1/2 size-4"/>
                        <input
                            class="h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 shadow-xs pl-10 text-lg"
                            id="amount"
                            name="amount"
                            placeholder="0,00"
                            required
                            type="number"
                            step="0.01"
                            min="0"
                            inputmode="numeric"
                        />
                    </div>
                </div>
                <button type="submit"
                        class="bg-fuchsia-600 hover:bg-fuchsia-500 text-gray-50 border border-white rounded-md w-full font-medium transition-all px-4 py-2 text-sm cursor-pointer">
                    Confirmar Depósito
                </button>
            </form>
        </div>
    </section>
</x-layout.main>
