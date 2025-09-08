@props([
    'fixedIncome' => null
])
<div
    class="relative isolate flex flex-col gap-6 rounded-xl border border-zinc-400 py-6 shadow-sm transition-colors hover:bg-fuchsia-50/30">
    <div class="p-4">
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <a href="{{route('fixed-income.detail', ['id' => $fixedIncome->id])}}">
                    <span class="absolute inset-0"></span>
                    <p class="font-semibold">{{$fixedIncome->name}}</p>
                    <p class="text-sm text-muted-foreground">{{$fixedIncome->type->getLabel()}}</p>
                </a>
                <div class="text-right">
                    <div>
                        <span class="text-xs">Vencimento:</span>
                        <p class="text-sm">{{$fixedIncome->maturity->format('d/m/Y')}}</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <div>
                        <span class="text-xs">Investimento mínimo:</span>
                        <p class="text-lg font-bold">{{Number::currency($fixedIncome->minimumInvestment, in: 'BRL')}}</p>
                    </div>
                    <div>
                        <span class="text-xs">Tipo de renda:</span>
                        <p class="text-sm font-medium">{{$fixedIncome->rateType->getLabel()}}</p>
                    </div>
                </div>
                <div class="text-right">
                    <div>
                        <span class="text-xs">Rentabilidade:</span>
                        <p class="text-sm text-green-600 font-medium">{{$fixedIncome->rate}}%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
