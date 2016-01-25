
require.config({
    baseUrl: '/js',
    urlArgs: "v=v0.0.1",
    paths: {
        jquery: "/components/jquery/dist/jquery.min",
        validate: "/components/validate/validate.min",
        "jquery.bootstrap.collapse": "/components/bootstrap/js/collapse",
    },
    shim: {
    	"jquery.bootstrap.collapse": {
            deps: ["jquery"]
        },

    }
});


requirejs(['main']);