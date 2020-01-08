<!doctype html>
<html lang="en">
    <head>
        <title>管理留言板</title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('common.head')
    </head>
    <body>
        <div class="container">
            @include('common.navbar')
            <div class="jumbotron">
                <h1>管理留言板</h1>
            </div>
            @include('common.error')
            @include('common.result')
            <!-- ajax error result -->
            <div class="alert alert-danger" id="error_message"></div>
            @if (3 > count($boards))
            <button class="btn btn-info" data-toggle="modal" data-target="#addBoard">新增留言板</button>
            @endif
            @foreach ($boards as $board)
            <h3><a href="/boards/<?= $board->board_id?>">{{ $board->title }}</a></h3>
            <p>{{ $board->describe }}</p>
            <button class="btn btn-danger delete_board" id="<?= $board->board_id ?>">刪除留言板</button>
            <hr>
            @endforeach
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addBoard" tabindex="-1" role="dialog" aria-labelledby="addBoardLable" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addBoardLable">新增留言板</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="board_title">名稱</label>
                            <input type="text" class="form-control" name="board_title" id="board_title" placeholder="請輸入留言板名稱">
                        </div>
                        <div class="form-group">
                            <label for="board_describe">敘述</label>
                            <textarea class="form-control" name="board_describe" id="board_describe" placeholder="請輸入留言板敘述"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                            <button type="button" class="btn btn-info" id="add_board">送出</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('common.script')
        <script>
            $('#error_message').hide();
            $(document).ready(function() {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('.delete_board').click(function(){
                    var id = $(this).attr('id');
                    $.ajax({
                        url: '/boards/' + id,
                        type: 'DELETE',
                        success: function(response) {
                        location.reload();
                        }
                    });
                });
                $('#add_board').click(function(){
                    var board_title = $('#board_title').val();
                    var board_describe = $('#board_describe').val();
                    $.ajax({
                        url: '/boards',
                        type: 'POST',
                        data: {
                            'board_title': board_title,
                            'board_describe': board_describe
                        },
                        success: function(response) {
                            location.reload();
                        },
                        error: function(response) {
                            if (422 == response.status) {
                                $('#error_message').html('');
                                $.each(response.responseJSON.errors, function(key,value) {
                                    $('#error_message').append('*' + value + '<br>');
                                });
                                $('#error_message').show();
                                $('#addBoard').modal('hide');
                            }
                        }
                    });
                });
            });
        </script>
    </body>
</html>
