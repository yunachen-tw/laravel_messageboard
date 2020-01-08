@if (session('result'))
    <div class="alert alert-{{session('alert-info')}}">
        {{ session('result') }}
    </div>
@endif
