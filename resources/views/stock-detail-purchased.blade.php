<x-layout.main back-to="{{ route('my-assets', ['type' => 'stocks']) }}" title="Detalhes da Ação">
    <div class="min-h-screen bg-fuchsia-50 py-8">
        <div class="px-4 sm:px-6">
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $stock->name }}</h1>
                        <p class="text-lg text-gray-600">{{ $stock->symbol }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-gray-900">
                            {{ Number::currency($stock->current_price, in: 'BRL') }}</p>
                        <p class="text-sm {{ $stock->daily_variation >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $stock->daily_variation >= 0 ? '+' : '' }}{{ $stock->daily_variation }}
                            %
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-fuchsia-100 rounded-xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Quantidade</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stockPurchaseDetail->quantity }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-fuchsia-100 rounded-xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Valor Investido</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ Number::currency($investedValue, in: 'BRL') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-fuchsia-100 rounded-xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Valor Atual</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ Number::currency($currentValue, in: 'BRL') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-fuchsia-100 rounded-xl shadow-sm p-6 border border-gray-200">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full {{ $profitLoss >= 0 ? 'bg-green-100' : 'bg-red-100' }}">
                            <svg class="w-6 h-6 {{ $profitLoss >= 0 ? 'text-green-600' : 'text-red-600' }}" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24">
                                @if($profitLoss >= 0)
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                @else
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                @endif
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">{{ $profitLoss >= 0 ? 'Lucro' : 'Prejuízo' }}</p>
                            <p class="text-2xl font-bold {{ $profitLoss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ Number::currency(abs($profitLoss), in: 'BRL') }}
                            </p>
                            <p class="text-sm {{ $profitLoss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $profitLoss >= 0 ? '+' : '-' }}{{ round(abs($profitLossPercentage), 2) }}%
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2">
                    <div class="bg-fuchsia-100 rounded-xl shadow-sm p-6 border border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Performance da Ação</h2>
                        <div class="h-80">
                            {!! $chart->render() !!}
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-fuchsia-100 rounded-xl shadow-sm p-6 border border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Detalhes da Compra</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Data da Compra</span>
                                <span class="font-medium text-gray-900">
                                    {{ \Carbon\Carbon::parse($stockPurchaseDetail->purchase_date)->format('d/m/Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Preço de Compra</span>
                                <span class="font-medium text-gray-900">
                                    {{ Number::currency($stockPurchaseDetail->purchase_price, in: 'BRL') }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Quantidade</span>
                                <span class="font-medium text-gray-900">
                                    {{ $stockPurchaseDetail->quantity }} ações
                                </span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Total Investido</span>
                                <span class="font-medium text-gray-900">
                                     {{ Number::currency($investedValue, in: 'BRL') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-fuchsia-100 rounded-xl shadow-sm p-6 border border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações da Empresa</h2>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Setor</span>
                                <span class="font-medium text-gray-900">{{ $stock->sector }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-gray-600">Símbolo</span>
                                <span class="font-medium text-gray-900">{{ $stock->symbol }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-gray-600">Preço Atual</span>
                                <span class="font-medium text-gray-900">
                                     {{ Number::currency($stock->current_price, in: 'BRL') }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-fuchsia-100 rounded-xl shadow-sm p-6 border border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Ações</h2>
                        <div class="space-y-3">
                            <form action="{{route('stock.sell-purchased', ['id' => $stockPurchaseDetail->id])}}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                    Vender Ações
                                </button>
                            </form>
                            <button
                                class="w-full border border-orange-600 text-orange-600 hover:bg-orange-50 font-medium py-3 px-4 rounded-lg transition-colors duration-200">
                                Comprar Mais
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <div class="bg-fuchsia-100 rounded-xl shadow-sm p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Análise de Performance</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="text-center p-4 bg-fuchsia-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Dias em Carteira</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ round(\Carbon\Carbon::parse($stockPurchaseDetail->purchase_date)->diffInDays(now())) }}
                            </p>
                        </div>
                        <div class="text-center p-4 bg-fuchsia-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Variação desde Compra</p>
                            <p class="text-2xl font-bold {{ $profitLoss >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $profitLoss >= 0 ? '+' : '' }}{{ Number::currency($profitLossPercentage, in: 'BRL') }}
                                %
                            </p>
                        </div>
                        <div class="text-center p-4 bg-fuchsia-50 rounded-lg">
                            <p class="text-sm text-gray-600 mb-1">Preço Médio</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ Number::currency($stockPurchaseDetail->purchase_price, in: 'BRL') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout.main>
