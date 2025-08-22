<x-layout.main>
    <h1>salve</h1>
    <p>{{Auth::user()->name}}</p>
    <form action="{{route('logout')}}" method="post">
        {{@csrf_field()}}
        <button type="submit">logout</button>
    </form>
</x-layout.main>
