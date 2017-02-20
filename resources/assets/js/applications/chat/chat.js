customModules.chatController = {
    spinnerTemplate: '<i class="fa fa-spinner fa-spin spinner"></i>',
    run : function(params) {
        var self = this;

        self.loadMessages();

        $(document).on('click', '#submitSend', function(e) {
            e.preventDefault();

            var btn = $(this);

            if (btn.hasClass('disabled')) {
                return false;
            }

            btn.button('Sending Message');
            btn.addClass('disabled');

            self.sendMessage(function () {
                btn.button('reset');
                btn.removeClass('disabled');
            });

            return false;
        });

        $(document).on('click', '.more', function(e) {
            e.preventDefault();

            var link = $(this);
            var url = link.attr('href');

            link.remove();
            self.loadMessages(url);

            return false;
        });
    },
    loadMessages: function (url, callback) {
        var self = this;
        var container = $('#messageContainer');

        container.prepend(self.spinnerTemplate);

        if ('undefined' == typeof url) {
            url = '/messages';
        }

        $.ajax({
            type: 'get',
            url: url,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 'success') {

                    $.each(response.messages, function(index, message) {
                        container.prepend(self.renderMessage(message));
                    });

                    if (response.next) {
                        container.prepend('<a class="more" href="' + response.next + '">Show more</a>');
                    }

                    if ('function' == typeof callback) {
                        callback(response);
                    }
                }
            },
            error: function(data){
                if( data.status === 422) {
                    var errors = data.responseJSON;
                    console.log(errors);

                }
            },
            complete: function () {
                $('.spinner', container).remove();
            }
        });
    },
    sendMessage: function(callback) {
        var self = this;
        var textarea = $('#message');
        var container = $('#messageContainer');
        $.ajax({
            type: 'post',
            url: '/chat',
            data: {
                message: textarea.val(),
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function (response) {
                if (response.status == 'success') {
                    textarea.val('');
                    container.append(self.renderMessage(response.message));
                    container.scrollTop(container[0].scrollHeight);
                }
            },
            error: function(data){
                if( data.status === 422) {
                    var errors = data.responseJSON;
                    console.log(errors);

                }
            },
            complete: function () {
                if ('function' == typeof callback) {
                    callback();
                }
            }
        });
    },
    renderMessage: function(message) {
        var messageTemplate = require("./../../templates/chat/message.html");
        return messageTemplate(message);
    }
};