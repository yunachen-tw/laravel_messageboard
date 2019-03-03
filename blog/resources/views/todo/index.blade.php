<!-- {{ $todos }} -->

@foreach ($todos as $todo)
    <p>{{ $todo->id . '. ' . $todo->title }}</p>
    <form action="/todo/{{$todo->id}}" method="POST">
        {{ csrf_field() }}
        {{ method_field('DELETE') }}
        <input type="submit" value="Delete">
    </form>
@endforeach

<form action="/todo" method="POST">
    {{ csrf_field() }}
    <input type="text" name="title" id="title" placeholder="Your Name">
    <input type="submit" value="提交">
</form>