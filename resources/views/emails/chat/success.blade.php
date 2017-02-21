<p>
    Your message has been successfully added
</p>

@if($messages)
    Messages by last hour:
    <br>
    @foreach ($messages as $message)
        <div class="media msg">
            <div class="media-body">
                <small class="pull-right time"><i class="fa fa-clock-o"></i> {{ $message->created_at->diffForHumans() }}</small>
                <b class="media-heading">{{ $message->user->name }}</b>
                <p>
                    <small class="col-lg-10">{{ $message->message }}</small>
                </p>
            </div>
        </div>
    @endforeach
@endif