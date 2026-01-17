@php use App\Support\MoneyHelper; @endphp
<x-layout.main title="{{$fixedIncome->name}} - {{$fixedIncome->type->getLabel()}}"
               back-to="{{route('assets', ['type' => 'fixed_income'])}}">
    <section class="max-w-6xl mx-auto grid grid-cols-3 gap-4 auto-rows-max">
        <div class="bg-fuchsia-200 p-6 flex flex-col gap-6 rounded-xl border col-span-2 border-gray-400 shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-2 auto-rows-auto">
                <div>
                    <span class="text-sm">Investimento Mínimo:</span>
                    <h2 class="text-3xl font-bold">
                        {{MoneyHelper::format($fixedIncome->minimumInvestment)}}
                    </h2>
                </div>
                <div class="col-start-1">
                    <span class="text-sm">Rentabilidade:</span>
                    <p class="text-lg text-green-600">
                        {{$fixedIncome->rate}}%
                    </p>
                </div>
                <div class="md:text-right md:col-start-2 md:row-start-1">
                    <span class="text-sm">Vencimento:</span>
                    <p>{{$fixedIncome->maturity->format('d/m/Y')}}</p>
                </div>
                <div class="md:text-right md:col-start-2 md:row-start-2">
                    <span class="text-sm">Tipo de renda:</span>
                    <p class="font-medium">{{$fixedIncome->rateType->getLabel()}}</p>
                </div>
            </div>
        </div>
        <div
            class="bg-fuchsia-200 p-6 col-start-3 flex flex-col gap-4 rounded-xl border border-gray-400 shadow-sm">
            <div class="flex items-center gap-1">
                <x-heroicon-o-currency-dollar class="size-4 inline-flex"/>
                <h2 class="text-sm font-medium">Disponível para investir</h2>
            </div>
            <p class="text-xl font-medium">
                {{MoneyHelper::format($investmentAccount->balance)}}
            </p>
        </div>
        <div
            class="bg-fuchsia-200 p-6 col-start-3 row-start-2 flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
            <div class="flex items-center gap-1">
                <x-carbon-piggy-bank class="size-4 inline-flex"/>
                <h2 class="text- font-medium">Investir</h2>
            </div>
            <form method="POST" action="{{route('fixed-income.buy', ['id' => $fixedIncome->id])}}" class="space-y-4">
                {{csrf_field()}}
                <div>
                    <label class="text-sm font-medium mb-2 block" for="amount">Valor do investimento</label>
                    <input class="w-full rounded-md border px-3 py-1.5 shadow-xs" id="amount"
                           name="amount"
                           required
                           placeholder="Digite o valor desejado"
                           step="0.01"
                           type="number" inputmode="numeric" min="{{$fixedIncome->minimumInvestment}}">
                </div>
                <button type="submit"
                        class="bg-fuchsia-600 hover:bg-fuchsia-500 text-gray-50 border border-white rounded-md w-full font-medium transition-all px-4 py-2 text-sm cursor-pointer">
                    Adquirir
                </button>
            </form>
        </div>
    </section>
</x-layout.main>
