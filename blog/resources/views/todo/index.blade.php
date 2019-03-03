<!-- {{ $todos }} -->

@foreach ($todos as $todo)
    <p>{{ $todo->id . '. ' . $todo->title }}</p>
@endforeach

<form action="/todo" method="POST">
    {{ csrf_field() }}
    <input type="text" name="title" id="title" placeholder="Your Name">
    <input type="submit" value="提交">
</form>