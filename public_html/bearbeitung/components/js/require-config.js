var require = {
    baseUrl: "components/js",
    paths: {
        'bootbox.min': 'libs/bootbox.min',
        'class': 'libs/class',
        'async': 'libs/async',
        'microevent': 'libs/microevent',
        'underscore': 'libs/underscore',
        'jquery.spinbox': 'jquery/jquery.spinbox',
        'jquery.mousewheel': 'jquery/jquery.mousewheel',
        'jquery.timeentry': 'jquery/jquery.timeentry'
    },
    shim: {
        'jquery.timeentry': [ 'jquery.mousewheel' ],
        'jquery.spinbox': [ 'jquery.mousewheel' ],
        'async': {
            exports: 'async'
        },
        'underscore': {
            exports: '_'
        },
        'libs/ajax-chosen': {
            deps: ['libs/chosen.jquery']
        },
        'ckeditor/adapters/jquery' : {
            deps: ['ckeditor/ckeditor']
        }
    }
};
