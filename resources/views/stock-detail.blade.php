@php use App\Support\MoneyHelper; @endphp
<x-layout.main back-to="{{route('assets', ['type' => 'stocks'])}}" title="{{$stock->name}} - {{$stock->symbol}}">
    <section class="max-w-6xl mx-auto grid grid-cols-3 grid-rows-4 gap-4">
        <div class="bg-fuchsia-200 col-span-2 row-span-1 p-6 flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <h2 data-slot="card-title" class="text-3xl font-bold">
                        {{MoneyHelper::format($stock->current_price)}}
                    </h2>
                    @if($stock->daily_variation > 0)
                        <p class="flex items-center text-lg text-green-600">
                            +R$&nbsp;1,23 ({{$stock->daily_variation}}%)
                        </p>
                    @else
                        <p class="flex items-center text-lg text-red-600">
                            +R$&nbsp;1,23 ({{$stock->daily_variation}}%)
                        </p>
                    @endif
                </div>
                <div class="text-right text-sm text-muted-foreground">
                    <p>Última atualização</p>
                    <p>{{$stock->updated_at->format('d/m/Y, H:i:s')}}</p>
                </div>
            </div>
        </div>
        <div class="bg-fuchsia-200 row-span-2 p-6 flex flex-col gap-6 h-fit rounded-xl border border-gray-400 shadow-sm">
            <div class="flex items-center gap-1">
                <x-heroicon-o-document-currency-dollar class="size-4 inline-flex"/>
                <h2 class="text- font-medium">Comprar ação</h2>
            </div>
            <form method="POST" action="{{route('stock.buy', ['stock' => $stock->id])}}" class="space-y-4">
                {{csrf_field()}}
                <div>
                    <label class="text-sm font-medium mb-2 block" for="quantity">Quantidade</label>
                    <input class="w-full rounded-md border px-3 py-1.5 shadow-xs" id="quantity"
                           name="quantity"
                           required
                           placeholder="Digite a quantidade desejada"
                           type="number" inputmode="numeric" min="0">
                </div>
                <button type="submit"
                        class="bg-fuchsia-600 hover:bg-fuchsia-500 text-gray-50 border border-white rounded-md w-full font-medium transition-all px-4 py-2 text-sm cursor-pointer">
                    Adquirir
                </button>
            </form>
        </div>
        <div class="bg-fuchsia-200 col-span-2 row-span-3 p-6 gap-6 rounded-xl border border-gray-400 shadow-sm">
            {{$chart->render()}}
        </div>
    </section>
</x-layout.main>
