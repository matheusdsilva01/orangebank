<x-layout.main>
    <div class="grid grid-cols-3 gap-4">
        <section class="grid md:grid-cols-2 gap-4 col-span-2">
            <div class="bg-fuchsia-200 p-6 flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
                <div class="flex items-center justify-between ">
                    <h3 class="text-sm font-medium">Conta Corrente</h3>
                    <x-eos-account-balance-wallet-o class="size-5"/>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-primary">
                        {{Number::currency($currentAccount->balance, in: 'BRL')}}
                    </h2>
                    <p class="text-xs">Disponível para saque</p></div>
            </div>
            <div class="bg-fuchsia-200 p-6 flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
                <div class="flex items-center justify-between ">
                    <h3 class="text-sm font-medium">Conta Investimento</h3>
                    <x-carbon-piggy-bank class="size-5"/>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-primary">
                        {{Number::currency($investmentAccount->balance, in: 'BRL')}}
                    </h2>
                    <p class="text-xs">Patrimõnio investido</p>
                </div>
            </div>
            <div
                class="bg-fuchsia-200 p-6 col-span-full flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
                <div class="flex items-center justify-between ">
                    <h3 class="text-sm font-medium">Patrimônio Total</h3>
                    <x-eos-trending-up class="size-5"/>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-primary">
                        {{Number::currency($investmentAccount->balance + $currentAccount->balance, in: 'BRL')}}
                    </h2>
                </div>
            </div>
        </section>
        <section
            class="bg-fuchsia-200 p-6 flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
            <h2 class="text- font-medium">Ações Rápidas</h2>
            <ul class="flex flex-col gap-4">
                <li class="border text-sm border-zinc-50 font-medium rounded-md hover:bg-indigo-400 hover:text-gray-50 transition-all cursor-pointer">
                    <a href="/" class="py-2 px-3 flex gap-2 items-center">
                        <x-heroicon-o-arrow-down-tray class="size-5"/>
                        Depositar
                    </a>
                </li>
                <li class="border text-sm border-zinc-50 font-medium rounded-md hover:bg-indigo-400 hover:text-gray-50 transition-all cursor-pointer">
                    <a href="/" class="py-2 px-3 flex gap-2 items-center">
                        <x-heroicon-o-arrow-up-tray class="size-5"/>
                        Sacar
                    </a>
                </li>
                <li class="border text-sm border-zinc-50 font-medium rounded-md hover:bg-indigo-400 hover:text-gray-50 transition-all cursor-pointer">
                    <a href="{{ route('transfer') }}" class="py-2 px-3 flex gap-2 items-center">
                        <x-heroicon-o-arrows-right-left class="size-5"/>
                        Transferir
                    </a>
                </li>
                <li class="border text-sm border-zinc-50 font-medium rounded-md bg-indigo-400 hover:bg-indigo-400/80 text-gray-50 transition-all cursor-pointer">
                    <a href="/" class="py-2 px-3 flex gap-2 items-center">
                        <x-heroicon-o-arrow-trending-up class="size-5"/>
                        Investir
                    </a>
                </li>
            </ul>
        </section>
        <section
            class="bg-fuchsia-200 p-6 col-span-full flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
            <div>
                <h3 class="text-lg font-medium">Histórico de Movimentações</h3>
                <p class="text-sm">Últimas 5 transações</p>
                <section class="flex flex-col gap-4">
                    @foreach($transactions as $transaction)
                        <div class="flex items-center rounded-md p-4 gap-4 border border-fuchsia-400 py-2">
                            <div class="">
                                <x-dynamic-component :component="$transaction->getIcon()" class="size-4"/>
                            </div>
                            <div class="flex flex-col">
                                @if ($transaction->isSender)
                                    <p class="font-medium">{{$transaction->receive->name}}</p>
                                @elseif (is_null($transaction->isSender))
                                    <p class="font-medium">Interna</p>
                                @else
                                    <p class="font-medium">{{$transaction->sender->name}}</p>
                                @endif
                                <span
                                    class="text-xs text-gray-600">{{ $transaction->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="ml-auto text-right">
                                @if (isset($transaction->isSender))
                                    <span
                                        class="font-bold {{ $transaction->isSender ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $transaction->isSender ? '-' : '+' }}{{ Number::currency($transaction->amount, in: 'BRL') }}
                                    </span>
                                @else
                                    <span class="font-bold text-blue-600">
                                        {{ Number::currency($transaction->amount, in: 'BRL') }}
                                    </span>
                                @endif
                                <p class="text-xs text-gray-600">{{ $transaction->fromAccount->getLabel() }}
                                    para {{$transaction->toAccount->getLabel()}}</p>
                            </div>
                        </div>
                    @endforeach
                </section>

            </div>
        </section>
    </div>
</x-layout.main>
