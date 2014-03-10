requirejs.config({
    baseUrl: "/14-18/js/admin",
    shim: {
        'jquery.i18n'          : ['jquery'],
        'jquery.i18n.messages' : ['jquery.i18n'],
        'jquery.i18n.fallbacks': ['jquery.i18n'],
        'jquery.i18n.parser'   : ['jquery.i18n'],
        'jquery.i18n.emitter'  : ['jquery.i18n'],
        'jquery.i18n.language' : ['jquery.i18n'],
        'jquery-ui'            : ['jquery']
    },
    paths: {
        'modernizr'            : '../vendor/modernizr-min',
        'jquery'               : '//code.jquery.com/jquery-2.1.0.min',
        'jquery-ui'            : '//code.jquery.com/ui/1.10.4/jquery-ui',
        'jquery.i18n'          : '../vendor/jquery.i18n/src/jquery.i18n',
        'jquery.i18n.messages' : '../vendor/jquery.i18n/src/jquery.i18n.messages',
        'jquery.i18n.fallbacks': '../vendor/jquery.i18n/src/jquery.i18n.fallbacks',
        'jquery.i18n.parser'   : '../vendor/jquery.i18n/src/jquery.i18n.parser',
        'jquery.i18n.emitter'  : '../vendor/jquery.i18n/src/jquery.i18n.emitter',
        'jquery.i18n.language' : '../vendor/jquery.i18n/src/jquery.i18n.language',
        'ckeditor'             : '../vendor/ckeditor/ckeditor'
    }
});

// Bootstrap
require(['app', 'modernizr'], function(App){
    App.initialize();
});