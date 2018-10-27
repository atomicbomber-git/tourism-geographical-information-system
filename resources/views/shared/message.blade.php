@if(session($session_key))
    <div class="alert alert-{{ $state }} my-3">
        <i class="fa fa-check"></i>
        {{ session($session_key) }}
    </div>
@endif