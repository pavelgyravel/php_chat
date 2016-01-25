define(["jquery", "jquery.bootstrap.collapse"], function($) {
    $(function($) {
        var $body = $('body'),
        	location = window.location.pathname;
        
        if(location.indexOf('messages/user') !== -1){
            requirejs(['pages/message']);
        }
    });
});