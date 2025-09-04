<div class="flex items-center rounded-md p-4 gap-4 border border-fuchsia-400 py-2">
    <div>
        <x-heroicon-o-arrow-down-tray class="size-4"/>
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
                class="font-bold text-green-600">
                {{ Number::currency($transaction->amount, in: 'BRL') }}
            </span>
        @else
            <span class="font-bold text-blue-600">
                {{ Number::currency($transaction->amount, in: 'BRL') }}
            </span>
        @endif
        <p class="text-xs text-gray-600">
            Depósito
        </p>
    </div>
</div>
