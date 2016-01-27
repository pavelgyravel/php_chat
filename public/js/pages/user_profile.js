define(["jquery"], function($, validate) {
    $(function($) {
        var delete_user = $('.JQ_delete_user');
        

        delete_user.on('click', function(e) {
            e.preventDefault();
            if (confirm("Delete user?")) {
                window.location = '/user/delete/' + delete_user.attr('id');
            }
        });
    });
});