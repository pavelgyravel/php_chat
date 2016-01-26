define(["jquery"], function($, validate) {
    $(function($) {
        var NEW_MESSAGES_INTERVAL = 3000;
        var UPDATE_USER_LIST_INTERVAL = 5000;
        var message = $('#JQ_message'),
        	form = $('.JQ_message_form'),
        	messages_container = $('.JQ_messages'),
        	last_input = $('.JQ_last'),
            chat_message = $(".JQ_chat_message"),
            users_list = $('#JQ_users_list'),
            xhr,
            interval;

        var updateMessages = function() {
           xhr = $.ajax({
                url: form.attr('action') + '/new',
                method: 'POST',
                data: form.serialize(),
                success: function(messages) {
                    
                    if (messages.length !== 0) {
                        for (var i = 0; i < messages.length; i++) {
                            var message = messageLayout(messages[i]);
                            messages_container.append(message);
                        }

                        scrollMessages();
                    
                        last_input.val(messages[messages.length-1].created_at);
                    }
                }
            }); 
        };
        updateMessages();

        interval = setInterval(updateMessages , NEW_MESSAGES_INTERVAL);

        var updateUserList = function() {
           $.ajax({
                url: '/messages/newMessages',
                method: 'GET',
                success: function(threads) {
                    users_list.find('.new_message').removeClass('new_message');
                    if (threads.length !== 0) {
                        for (var i = 0; i < threads.length; i++) {
                            users_list.find('#JQ_thread_' + threads[i].id).addClass('new_message');
                        }
                    }
                }
            }); 
        };

        setInterval(updateUserList , UPDATE_USER_LIST_INTERVAL);

        form.submit(function(e) {
    		e.preventDefault();
            if (chat_message.val()) {
                
                xhr.abort();
                clearInterval(interval);

        		$.ajax({
        			url: form.attr('action'),
        			method: 'POST',
        			data: form.serialize(),
                    beforeSend: function() {
                        chat_message.val('');
                        // form.find('submit, input').prop('disabled', true);
                    },
        			success: function(messages) {
        				for (var i = 0; i < messages.length; i++) {
        					var message = messageLayout(messages[i]);
    						messages_container.append(message);
        				}
                        scrollMessages();
        				last_input.val(messages[messages.length-1].created_at);
                        // form.find('submit, input').prop('disabled', false);
                        interval = setInterval(updateMessages , NEW_MESSAGES_INTERVAL);
        			}
        		});
            }
        });

       var messageLayout = function(message) {

            return  '<div class="media animate-message">' +
                        '<div class="media-body">' +
                            '<h5 class="media-heading"><b>'+ message.user.name +'</b> <small>(Posted ' +message.created_at+ ')</small></h5>' +
                            '<p>' +message.body+ '</p>' +
                        '</div>' +
                    '</div>';
       };

       

       function scrollMessages() {
            messages_container.scrollTop(messages_container.prop('scrollHeight'));
        };
        scrollMessages();
    });
});