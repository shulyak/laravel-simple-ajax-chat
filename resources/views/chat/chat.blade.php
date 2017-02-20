@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Chat</div>

                <div class="panel-body">

                    <div class="msg-wrap" id="messageContainer"></div>

                    <div class="form-group">

                        <textarea class="form-control send-message" rows="3" placeholder="Write a message..." id="message"></textarea>

                    </div>

                    <div class="form-group">
                        <a href="" class=" col-lg-4 text-right btn btn-primary pull-right" id="submitSend" role="button">
                            <i class="fa fa-plus"></i> Send Message
                        </a>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        window.modules.chatController = {};
    </script>
@endpush
