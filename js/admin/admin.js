requirejs.config({
    baseUrl: "/14-18/js/admin",
    shim: {
        'jquery.i18n'          : ['jquery'],
        'jquery.i18n.messages' : ['jquery.i18n'],
        'jquery.i18n.fallbacks': ['jquery.i18n'],
        'jquery.i18n.parser'   : ['jquery.i18n'],
        'jquery.i18n.emitter'  : ['jquery.i18n'],
        'jquery.i18n.language' : ['jquery.i18n'],
        'jquery-ui'            : ['jquery'],
        'bootstrap'            : ['jquery'],
        'bootstrap-collection' : ['jquery'],
        'chosen'               : ['jquery'],
        'TextboxList'          : ['jquery', 'GrowingInput'],
        'TextboxList.Autocomplete' : ['TextboxList']
    },
    paths: {
        'Class'                : '../vendor/Class',
        'modernizr'            : '../vendor/modernizr-min',
        'jquery'               : '//code.jquery.com/jquery-2.1.0.min',
        'jquery-ui'            : '//code.jquery.com/ui/1.10.4/jquery-ui',
        'jquery.i18n'          : '../vendor/jquery.i18n/src/jquery.i18n',
        'jquery.i18n.messages' : '../vendor/jquery.i18n/src/jquery.i18n.messages',
        'jquery.i18n.fallbacks': '../vendor/jquery.i18n/src/jquery.i18n.fallbacks',
        'jquery.i18n.parser'   : '../vendor/jquery.i18n/src/jquery.i18n.parser',
        'jquery.i18n.emitter'  : '../vendor/jquery.i18n/src/jquery.i18n.emitter',
        'jquery.i18n.language' : '../vendor/jquery.i18n/src/jquery.i18n.language',
        'bootstrap'            : '../bootstrap/bootstrap.min',
        'bootstrap-collection' : 'bootstrap-collection',
        'ckeditor'             : '../vendor/ckeditor/ckeditor',
        'chosen'               : '../vendor/chosen/chosen.jquery.min',
        'GrowingInput'         : '../vendor/TextboxList/GrowingInput',
        'TextboxList'          : '../vendor/TextboxList/TextboxList',
        'TextboxList.Autocomplete' : '../vendor/TextboxList/TextboxList.Autocomplete'
    }
});

// Bootstrap
require(['app', 'modernizr', 'bootstrap'], function(App){
    App.initialize();
});