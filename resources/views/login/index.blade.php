<!doctype html>
<html lang="en">
    <head>
        <title>留言板登入</title>
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
                <h1>留言板</h1>
            </div>
            @include('common.error')
            <!-- ajax error result -->
            <div class="alert alert-danger" id="error_message"></div>
            <div class="col-md-6 offset-md-3 border rounded px-5 py-4" >
                <h4 class="text-center">登入</h4>
                    <div class="form-group">
                        <label for="account">帳號</label>
                        <input type="text" class="form-control" name="account" id="account" placeholder="Enter Account" value="">
                    </div>
                    <div class="form-group">
                        <label for="password">密碼</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password" value="">
                    </div>
                    <div class="text-center pt-4">
                        <button type="button" class="btn btn-info" id="login">登入</button>
                        <a href="/register"><button type="button" class="btn btn-dark">註冊</button></a>
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
                $('#login').click(function(){
                    var account = $('#account').val();
                    var password = $('#password').val();
                    $.ajax({
                        url: '/login/check',
                        type: 'POST',
                        data: {
                            'account': account,
                            'password': password
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
                            if (403 == response.status) {
                                $('#error_message').html(response.responseJSON.message);
                                $('#error_message').show();
                            }
                        }
                    });
                });
            });
        </script>
    </body>
</html>
