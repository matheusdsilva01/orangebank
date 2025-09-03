<x-layout.main title="Transferência" :back-to="route('dashboard')">
    <section class="flex rounded-md p-1 bg-fuchsia-200 gap-1.5 md:mt-8">
        <a href="{{route('transfer', ['type' => 'internal'])}}" data-selected="@bool($type==='internal')"
           class="py-1 px-2 rounded-md cursor-pointer data-[selected=true]:bg-fuchsia-300 data-[selected=true]:shadow-sm">Interna</a>
        <a href="{{route('transfer', ['type' => 'external'])}}" data-selected="@bool($type==='external')"
           class="py-1 px-2 rounded-md cursor-pointer data-[selected=true]:bg-fuchsia-300 data-[selected=true]:shadow-sm">Externa</a>
    </section>
    <div
        class="bg-fuchsia-200 mt-2 flex flex-col gap-6 rounded-xl border p-6 shadow-sm"
    >
        <div>
            <h1 class="text-xl font-semibold">
                Realizar Transferência
            </h1>
            <p class="text-gray-900 text-sm">
                Selecione o tipo de transferência e preencha os dados
            </p>
        </div>
        @if ($type==='external')
            <form class="space-y-6" method="POST" action="{{ route('transfer.external') }}">
                {{@csrf_field()}}
                <div class="space-y-2">
                    <label
                        class="text-sm"
                        for="amount"
                    >
                        Valor da Transferência
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
                <div class="space-y-2">
                    <label
                        class="flex items-center gap-2 text-sm leading-none font-medium select-none group-data-[disabled=true]:pointer-events-none group-data-[disabled=true]:opacity-50 peer-disabled:cursor-not-allowed peer-disabled:opacity-50"
                        for="destination"
                    >
                        Número da Conta de Destino </label
                    ><input
                        class="h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 shadow-xs text-lg"
                        placeholder="Digite o número da conta"
                        id="destination"
                        name="destination"
                        type="text"
                        required
                    />
                </div>
                <button type="submit"
                        class="bg-fuchsia-600 hover:bg-fuchsia-500 text-gray-50 border border-white rounded-md w-full font-medium transition-all px-4 py-2 text-sm cursor-pointer">
                    Confirmar Transferência
                </button>
            </form>
        @else
            <form class="space-y-6" method="POST" action="{{ route('transfer.internal') }}">
                {{@csrf_field()}}
                <div class="space-y-2">
                    <label
                        class="text-sm"
                        for="amount"
                    >
                        Valor da Transferência
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
                <fieldset class="space-y-2">
                    <legend
                        class="text-sm font-medium"
                    >
                        Tipo
                    </legend>
                    <label class="flex items-center gap-1">
                        <input type="radio" name="mode" id="investment-current" value="investment-current" />
                        <p>
                            Conta Investimento para Conta Corrente
                        </p>
                    </label>
                    <label class="flex items-center gap-1">
                        <input required type="radio" name="mode" id="current-investment" value="current-investment" />
                        <p>
                            Conta Corrente para Conta Investimento
                        </p>
                    </label>
                </fieldset>
                <button type="submit"
                        class="bg-fuchsia-600 hover:bg-fuchsia-500 text-gray-50 border border-white rounded-md w-full font-medium transition-all px-4 py-2 text-sm cursor-pointer">
                    Confirmar Transferência
                </button>
        @endif
    </div>
</x-layout.main>
