define(["jquery"], function($, validate) {
    $(function($) {
        var message = $('#JQ_message'),
        	form = $('.JQ_message_form'),
        	messages_container = $('.JQ_messages'),
        	last_input = $('.JQ_last'),
            xhr;
        
        form.submit(function(e) {
    		e.preventDefault();
            xhr.abort();
    		$.ajax({
    			url: form.attr('action'),
    			method: 'POST',
    			data: form.serialize(),
    			success: function(messages) {
    				for (var i = 0; i < messages.length; i++) {
    					var message = messageLayout(messages[i]);
						messages_container.append(message);
    				}
    			
    				last_input.val(messages[messages.length-1].created_at);
    			}
    		});
        });

        var updateMessages = function() {
           xhr = $.ajax({
                url: form.attr('action') + '/new',
                method: 'POST',
                data: form.serialize(),
                success: function(messages) {
                    if (messages) {
                        for (var i = 0; i < messages.length; i++) {
                            var message = messageLayout(messages[i]);
                            messages_container.append(message);
                        }

                    
                        last_input.val(messages[messages.length-1].created_at);
                    }
                }
            }); 
       };

       var messageLayout = function(message) {
            return  '<div class="media">' +
                        '<div class="media-body">' +
                            '<h5 class="media-heading"><b>'+ message.user.name +'</b> <small>(Posted ' +message.created_at+ ')</small></h5>' +
                            '<p>' +message.body+ '</p>' +
                        '</div>' +
                    '</div>';
       };

       setInterval(updateMessages , 5000);
    });
});