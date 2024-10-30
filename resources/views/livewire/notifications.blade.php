<div>
    @foreach($notifications as $notification)
        <div class="alert alert-{{ $notification['type'] }}">
            {{ $notification['message'] }}
        </div>
    @endforeach
</div>
