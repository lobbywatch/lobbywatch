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
        'jquery.timeentry': 'jquery/jquery.timeentry',
        'jquery.bind-first': 'jquery/jquery.bind-first',
        'jquery.plugin': 'jquery/jquery.plugin'
    },
    shim: {
        'jquery.timeentry': [ 'jquery.plugin', 'jquery.mousewheel' ],
        'jquery.spinbox': [ 'jquery.mousewheel' ],
        'async': {
            exports: 'async'
        },
        'underscore': {
            exports: '_'
        },
        'ckeditor/adapters/jquery' : {
            deps: ['ckeditor/ckeditor']
        }
    }
};
