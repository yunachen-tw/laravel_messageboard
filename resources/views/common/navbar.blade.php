<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <a class="nav-link" href="/boardlist">首頁</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/boardlist">留言板列表</a>
            </li>
            @if (Auth::check())
                <li class="nav-item">
                    <a class="nav-link" href="/manage">管理留言板</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/logout">登出</a>
                </li>
            @else
                <li class="nav-item">
                    <a class="nav-link" href="/login">登入</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/register">註冊</a>
                </li>
            @endif
        </ul>
    </div>
</nav>
