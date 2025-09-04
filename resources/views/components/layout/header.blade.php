@aware(
    [
        'title' => null,
        'backTo' => null
    ]
)
<header class="w-full px-2 py-3 shadow-sm bg-fuchsia-200">
    <nav class="max-w-7xl mx-auto flex items-center justify-between">
        <div class="flex gap-4 items-center">
            @if (isset($backTo))
                <div class="flex items-center gap-2 relative isolate hover:bg-fuchsia-400 px-2.5 rounded-md transition-all hover:text-gray-50">
                    <x-heroicon-c-arrow-left class="size-4"/>
                    <a href="{{$backTo}}" class="block z-10">
                        <span class="absolute inset-0"></span>
                        Voltar
                    </a>
                </div>
            @endif
            <h1 class="font-bold text-2xl text-fuchsia-950">{{$title ?? 'Orange Bank'}}</h1>
        </div>
        <ul class="flex gap-4">
            <li class="flex items-center gap-1">
                <span class="rounded-full p-1.5 bg-fuchsia-100">
                    <x-eos-person-outline-o class="size-4"/>
                </span>
                <p>{{explode(' ', Auth::user()->name)[0] . ' ' . explode(' ', Auth::user()->name)[1]}}</p>
            </li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    {{@csrf_field()}}
                    <button type="submit"
                            class="hover:bg-fuchsia-500 hover:text-gray-50 rounded-md transition-all p-2 cursor-pointer">
                        <x-eos-logout class="size-4"/>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</header>
