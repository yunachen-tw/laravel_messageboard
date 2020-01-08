<!doctype html>
<html lang="en">
    <head>
        <title>留言板列表</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        @include('common.head')
    </head>
    <body>
        <div class="container">
            @include('common.navbar')
            <div class="jumbotron">
            @if (session('account'))
                <h1>{{ session('account') }} 的留言板列表</h1>
            @else
                <h1>留言板列表</h1>
            @endif
            </div>
            @include('common.result')
            @foreach ($boards as $board)
                <h3><a href="/boards/<?= $board->board_id?>">{{ $board->title }}</a></h3>
                <p>{{ $board->describe }}</p>
                <p class="float-right">版主 <a href="/boardlist/user/<?= $board->user->user_id?>">{{ $board->user->account }}</a></p>
                <p><small>最後更新: {{ $board->updated_at }}</small></p>
                <hr>
            @endforeach
            {{ $boards->links() }}
        </div>
        @include('common.script')
    </body>
</html>
