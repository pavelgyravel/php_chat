define(["jquery", "jquery.bootstrap.collapse"], function($) {
    $(function($) {
        var $body = $('body'),
        	location = window.location.pathname;
        
        if(location.indexOf('messages/user') !== -1){
            requirejs(['pages/message']);
        } else if (location.indexOf('messages') !== -1){ 
        	requirejs(['pages/check_new_message']);
        } else if (location.indexOf('user/profile') !== -1) {
        	requirejs(['pages/user_profile']);
        }
    });
});