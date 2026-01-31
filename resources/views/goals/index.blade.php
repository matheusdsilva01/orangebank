<x-layout.main back-to="{{route('dashboard')}}" title="Minhas Metas">
    <section class="max-w-5xl mx-auto space-y-8">
        <div class="bg-fuchsia-200 p-6 flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
            <div class="flex items-center justify-between">
                <h3 class="text-sm font-medium">Progresso das Metas</h3>
                <x-heroicon-o-flag class="size-5"/>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-primary">
                    {{ $goalProgress->count() }}
                </h2>
                <p class="text-xs">Metas em andamento</p>
            </div>
        </div>

        <div class="bg-fuchsia-200 p-6 flex flex-col gap-6 rounded-xl border border-gray-400 shadow-sm">
            <div>
                <h3 class="text-sm font-medium">Suas Metas</h3>
                <p>Acompanhe o progresso de suas metas</p>
            </div>
            <section class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($goalProgress as $progress)
                    @php
                        $goal = $progress->goal;
                        $percentage = min(100, ($progress->progress / $goal->threshold) * 100);
                        $isCompleted = $progress->completed;
                    @endphp
                    <div class="bg-fuchsia-100 p-4 rounded-lg border border-gray-300 shadow-sm">
                        <div class="flex items-start justify-between mb-3">
                            <div>
                                <h4 class="font-medium text-fuchsia-950">{{ $goal->name }}</h4>
                                <p class="text-xs text-gray-600">{{ $goal->description }}</p>
                            </div>
                        @if($isCompleted)
                                <span class="bg-green-500 text-white text-xs px-2 py-1 rounded-full">Concluída</span>
                            @else
                                <span class="bg-fuchsia-400 text-white text-xs px-2 py-1 rounded-full">Em progresso</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            <div class="flex justify-between text-xs mb-1">
                                <span>{{ $progress->progress }} / {{ $goal->threshold }}</span>
                                <span>{{ number_format($percentage, 0) }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-2.5">
                                <div class="h-2.5 rounded-full transition-all {{ $isCompleted ? 'bg-green-500' : 'bg-indigo-400' }}"
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500">
                            Atualizado em {{ $progress->updated_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                @empty
                    <div class="col-span-full text-center py-8">
                        <x-heroicon-o-flag class="size-12 mx-auto text-gray-400 mb-3"/>
                        <p class="text-gray-500">Você ainda não possui metas em andamento</p>
                    </div>
                @endforelse
            </section>
        </div>
    </section>
</x-layout.main>
