<x-layout.main back-to="{{route('dashboard')}}" title="Investimentos">
    <section class="max-w-5xl mx-auto space-y-8">
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
        <section class="flex rounded-md p-1 bg-fuchsia-200 gap-1.5 md:mt-8">
            <a href="{{route('assets', ['type' => 'stocks'])}}" data-selected="@bool($type==='stocks')"
               class="py-1 px-2 rounded-md cursor-pointer data-[selected=true]:bg-fuchsia-300 data-[selected=true]:shadow-sm">Ações</a>
            <a href="{{route('assets', ['type' => 'fixed_income'])}}" data-selected="@bool($type==='fixed_income')"
               class="py-1 px-2 rounded-md cursor-pointer data-[selected=true]:bg-fuchsia-300 data-[selected=true]:shadow-sm">Renda
                fixa</a>
        </section>
        <div class="bg-fuchsia-200 p-6 flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
            <div>
                <h3 class="text-sm font-medium">Ativos disponíveis</h3>
                <p>Selecione um ativo para investir</p>
            </div>
            <section class="grid grid-cols-1 md:grid-cols-3 gap-3">
                @if($type === 'stocks')
                    @forelse($stocks as $stock)
                        <x-card-stock :stock="$stock"/>
                    @empty
                        <p class="text-sm text-gray-500">Nenhum ativo disponível no momento</
                    @endforelse
                @else
                    @forelse($fixedIncomes as $fixedIncome)
                        {{$fixedIncome->name}} <br/>
                    @empty
                        <p class="text-sm text-gray-500">Nenhum ativo disponível no momento</p>
                    @endforelse
                @endif
            </section>
        </div>
    </section>
</x-layout.main>
