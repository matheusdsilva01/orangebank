<div
    class="relative isolate flex flex-col gap-6 rounded-xl border border-zinc-400 px-4 py-6 shadow-sm transition-colors hover:bg-fuchsia-50/30">
    <header class="flex justify-between">
        <div>
            <span class="text-xs">Valor investido: </span>
            <p class="text-lg font-bold">
                {{Number::currency($fixedIncome->pivot->amount_investment, in: 'BRL')}}
            </p>
        </div>
        <div class="text-right">
            <span class="text-xs">Maturidade: </span>
            <p class="text-sm font-bold">
                {{$fixedIncome->maturity->format('d/m/Y')}}
            </p>
        </div>
    </header>
    <div class="flex justify-between">
        <div>
            <div>
                <span class="text-xs">Nome:</span>
                <p class="font-semibold">{{$fixedIncome->name}}</p>
            </div>
            <div>
                <span class="text-xs">Tipo:</span>
                <p class="font-semibold">{{$fixedIncome->type->getLabel()}}</p>
            </div>
            <div>
                <span class="text-xs">Renda obtida:</span>
                <p class="font-semibold">{{Number::currency($fixedIncome->pivot->amount_earned, in: 'BRL')}}</p>
            </div>
        </div>
        <div class="text-right">
            <div>
                <span class="text-xs">Tipo de renda:</span>
                <p class="font-semibold">{{$fixedIncome->rateType->getLabel()}}</p>
            </div>
            <div>
                <span class="text-xs">Rentabilidade:</span>
                <p class="font-semibold">
                    {{$fixedIncome->rate}}%
                </p>
            </div>
        </div>
    </div>
    <form action="{{route('fixed-income.sell', ['accountFixedIncome' => $fixedIncome->pivot->id])}}" method="POST">
        @csrf
        <button
            class="rounded w-full bg-fuchsia-600 px-3 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-fuchsia-500 cursor-pointer"
        >Vender</button>
    </form>
</div>
