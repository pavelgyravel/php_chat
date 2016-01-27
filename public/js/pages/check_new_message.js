define(["jquery"], function($, validate) {
    $(function($) {
        var UPDATE_USER_LIST_INTERVAL = 3000;
        var users_list = $('#JQ_users_list');

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
    });
});