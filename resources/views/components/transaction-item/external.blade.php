<div class="flex items-center rounded-md p-4 gap-4 border border-fuchsia-400 py-2">
    @if ($transaction->isSender)
        <div>
            <x-heroicon-o-arrow-up-tray class="size-4"/>
        </div>
        <div class="flex flex-col">
                <p class="font-medium">{{$transaction->receive->name}}</p>
            <span
                class="text-xs text-gray-600">{{ $transaction->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</span>
        </div>
        <div class="ml-auto text-right">
            <span
                class="font-bold text-red-600">
                {{ Number::currency(-$transaction->amount, in: 'BRL') }}
            </span>
            <p class="text-xs text-gray-600">De: {{ $transaction->fromAccount->getLabel() }}
                para: {{ $transaction->toAccount->getLabel() }}</p>
        </div>
    @else
        <div>
            <x-heroicon-o-arrow-down-tray class="size-4"/>
        </div>
        <div class="flex flex-col">
            <p class="font-medium">{{$transaction->sender->name}}</p>
            <span
                class="text-xs text-gray-600">{{ $transaction->created_at->timezone('America/Sao_Paulo')->format('d/m/Y H:i') }}</span>
        </div>
        <div class="ml-auto text-right">
            <span
                class="font-bold text-green-600">
                {{ Number::currency($transaction->amount, in: 'BRL') }}
            </span>
            <p class="text-xs text-gray-600">
                De: Conta Corrente
                para: Conta Corrente
            </p>
        </div>
    @endif
</div>
