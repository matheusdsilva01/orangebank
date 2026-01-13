@php use App\Support\MoneyHelper; @endphp
<div class="flex items-center rounded-md p-4 gap-4 border border-fuchsia-400 py-2">
    <div>
        <x-heroicon-o-arrows-right-left class="size-4"/>
    </div>
    <div class="flex flex-col">
        <p class="font-medium">Interna</p>
        <span
            class="text-xs text-gray-600">{{ $transaction->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</span>
    </div>
    <div class="ml-auto text-right">
        <span class="font-bold text-blue-600">
            {{ MoneyHelper::format($transaction->amount) }}
        </span>
        <p class="text-xs text-gray-600">De: {{ $transaction->fromAccount->getLabel() }}
            para: {{ $transaction->toAccount->getLabel() }}</p>
    </div>
</div>
