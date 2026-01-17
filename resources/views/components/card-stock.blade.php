@php use App\Support\MoneyHelper; @endphp
@props([
    'stock' => null
])
<div
    class="relative isolate flex flex-col gap-6 rounded-xl border border-zinc-400 py-6 shadow-sm transition-colors hover:bg-fuchsia-50/30">
    <div class="p-4">
        <div class="space-y-2">
            <div class="flex items-center justify-between">
                <a href="{{route('stock.detail', ['id' => $stock->id])}}">
                    <span class="absolute inset-0"></span>
                    <p class="font-semibold">{{$stock->symbol}}</p>
                    <p class="text-sm text-muted-foreground">{{$stock->name}}</p>
                </a>
            </div>
            <div class="flex items-center justify-between">
                <p class="text-lg font-bold">
                    {{MoneyHelper::format($stock->current_price)}}
                </p>
                @if($stock->daily_variation > 0)
                    <div class="flex items-center text-green-600">
                        <x-heroicon-c-arrow-trending-up class="size-6"/>
                        <p>{{$stock->daily_variation}}%</p>
                    </div>
                @else
                    <div class="flex items-center text-red-600">
                        <x-heroicon-c-arrow-trending-down class="size-6"/>
                        <p>{{$stock->daily_variation}}%</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
