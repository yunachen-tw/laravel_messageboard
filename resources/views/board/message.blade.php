<!doctype html>
<html lang="en">
    <head>
        <title>留言板 - {{ $board->title }}</title>
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
                <h1>留言板 - {{ $board->title }}</h1>
                <p>{{ $board->describe }}</p>
            </div>
            @include('common.result')
            <!-- ajax error result -->
            <div class="alert alert-danger" id="error_message"></div>
            @foreach ($messages as $message)
                <div id="<?= $message->message_id?>">
                    @if ($message->deletable(Auth::user()->user_id))
                        <button class="btn btn-danger float-right delete_message">刪除留言</button>
                    @endif
                    <h5><a href="/boardlist/user/<?= $message->user_id?>" class="badge badge-secondary badge-pill">{{ $message->user->account }}</a></h5>
                    <p>{!! nl2br($message->content) !!}</p>
                    @php($total_score = 0)
                    @php($my_score = 0)
                    @foreach ($scores[$message->message_id] as $score)
                        @php($total_score += $score->score)
                        @if ($score->user_id == Auth::user()->user_id)
                            @php($my_score = $score->score)
                        @endif
                    @endforeach
                    <span class="badge badge-pill badge-light">
                        <span class="total_score">{{ $total_score }}</span>
                        <i class="fas fa-star"></i>
                    </span>
                    @if (1 == $my_score)
                        <i title="+1" class="far fa-laugh-beam fa-lg score_message" style="color: #F1D483" data-value="1"></i>
                        <i title="0" class="far fa-grimace fa-lg score_message" style="color: #AEB7B3" data-value="0"></i>
                        <i title="-1" class="far fa-frown fa-lg score_message" style="color: #AEB7B3" data-value="-1"></i>
                    @elseif (-1 == $my_score)
                        <i title="+1" class="far fa-laugh-beam fa-lg score_message" style="color: #AEB7B3" data-value="1"></i>
                        <i title="0" class="far fa-grimace fa-lg score_message" style="color: #AEB7B3" data-value="0"></i>
                        <i title="-1" class="far fa-frown fa-lg score_message" style="color: #5998C5" data-value="-1"></i>
                    @else
                        <i title="+1" class="far fa-laugh-beam fa-lg score_message" style="color: #AEB7B3" data-value="1"></i>
                        <i title="0" class="far fa-grimace fa-lg score_message" style="color: #AEB7B3" data-value="0"></i>
                        <i title="-1" class="far fa-frown fa-lg score_message" style="color: #AEB7B3" data-value="-1"></i>
                    @endif
                </div>
                <hr>
            @endforeach
            {{ $messages->links() }}
            <button class="btn btn-info" data-toggle="modal" data-target="#leaveMessage">我要留言</button>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="leaveMessage" tabindex="-1" role="dialog" aria-labelledby="leaveMessageLable" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="leaveMessageLable">歡迎留言</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <textarea class="form-control" name="message_content" id="message_content" placeholder="請輸入留言"></textarea>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">關閉</button>
                            <button type="button" class="btn btn-info" id="leave_message">送出</button>
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
                $('#leave_message').click(function(){
                    var message_content = $('#message_content').val();
                    var board_id = <?= $board->board_id ?>;
                    $.ajax({
                        url: '/messages',
                        type: 'POST',
                        data: {
                            'board_id': board_id,
                            'message_content': message_content
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
                                $('#leaveMessage').modal('hide');
                            }
                        }
                    });
                });
                $('.delete_message').click(function(){
                    var id = $(this).parent().attr('id');
                    $.ajax({
                        url: '/messages/' + id,
                        type: 'DELETE',
                        success: function(response) {
                            location.reload();
                        }
                    });
                });
                $('.score_message').click(function(){
                    var id = $(this).parent().attr('id');
                    var score = $(this).data('value');
                    var total_score = $('#' + id).find('span.total_score').html();
                    $.ajax({
                        url: '/messages/' + id + '/scores',
                        type: 'POST',
                        context: this,
                        data: {
                            'score': score,
                        },
                        success: function(response) {
                            var new_score = parseInt(total_score) + parseInt(response['data']['score_change']);
                            $('#' + id).find('span.total_score').html(new_score);
                            $(this).siblings('.score_message').css('color', '#AEB7B3');
                            if (1 == score) {
                                $(this).css('color', '#F1D483');
                            } else if(-1 == score) {
                                $(this).css('color', '#5998C5');
                            }
                        }
                    });
                });
            });
        </script>
    </body>
</html>
