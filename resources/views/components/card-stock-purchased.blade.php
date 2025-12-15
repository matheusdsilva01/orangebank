<div
    class="relative isolate flex flex-col gap-6 rounded-xl border border-zinc-400 px-4 py-6 shadow-sm transition-colors hover:bg-fuchsia-50/30">
    <header class="flex justify-between">
        <div>
            <span class="text-xs">Valor atual: </span>
            <p class="text-lg font-bold">
                {{Number::currency($stock->current_price, in: 'BRL')}}
            </p>
        </div>
        <div class="text-right">
            <span class="text-xs">Preço na compra: </span>
            <div class="flex gap-1">
                <p class="text-sm font-semibold">{{Number::currency($stock->pivot->purchase_price, in: 'BRL')}}</p>
                @if($variation > 0)
                    <div class="flex text-xs items-center text-green-600">
                        <p>{{$variation}}%</p>
                        <x-heroicon-c-arrow-trending-up class="size-3"/>
                    </div>
                @else
                    <div class="flex text-xs items-center text-red-600">
                        <p>{{$variation}}%</p>
                        <x-heroicon-c-arrow-trending-down class="size-3"/>
                    </div>
                @endif
            </div>
        </div>
    </header>
    <div>
        <div class="flex justify-between">
            <a href="{{route('stock.detail-purchased', ['id' => $stock->pivot->id])}}">
                <span class="absolute inset-0"></span>
                <div>
                    <span class="text-xs">Nome: </span>
                    <p class="text-sm font-semibold">{{$stock->name}}</p>
                </div>
                <div>
                    <span class="text-xs">Código: </span>
                    <p class="font-semibold">{{$stock->symbol}}</p>
                </div>
                <div>
                    <span class="text-xs">Data da compra: </span>
                    <p class="font-semibold">{{$stock->pivot->purchase_date->format('d/m/Y H:i')}}</p>
                </div>
            </a>
            <div class="text-right">
                <div>
                    <span class="text-xs">Rentabilidade: </span>
                    <p class="text-lg font-bold">
                        {{Number::currency($stock->current_price - $stock->pivot->purchase_price, in: 'BRL')}}
                    </p>
                </div>
                <div>
                    <span class="text-xs">Quantidade adquirida: </span>
                    <p class="text-sm font-semibold">{{$stock->pivot->quantity}}</p>
                </div>
            </div>
        </div>
    </div>
</div>
