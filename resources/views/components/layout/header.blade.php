<header class="w-full px-2 py-3 shadow-sm bg-fuchsia-200">
    <nav class="max-w-7xl mx-auto flex items-center justify-between">
        <h1 class="font-medium text-xl text-fuchsia-950">Orange Bank</h1>
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
