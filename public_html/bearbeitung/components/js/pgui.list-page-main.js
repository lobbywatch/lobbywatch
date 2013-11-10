define(function(require, exports, module) {

    var pc  = require('pgui.pagination'), 
        dtp = require('pgui.datetimepicker'), 
        Class = require('class'), 
        sc = require('pgui.shortcuts');

    $(function() {
        pc.setupPaginationControls($('body'));
        dtp.setupCalendarControls($('body'));

        $('[data-pg-typeahead=true]').each(function() {
            var typeHeadInput = $(this);

            require(['pgui.typeahead'], function(pt) {
                (new pt.PgTypeahead(typeHeadInput));
            })
        });

        $('body').find('.more_hint').each(function() {
            var $hintLink = $(this);

            $hintLink.find('a:first').popover({
                title: '',
                placement: function() {
                    if ($hintLink.offset().top - $(window).scrollTop() < $(window).height() / 2)
                        return 'bottom';
                    else
                        return 'top';
                },
                html : true,
                content: $hintLink.find('.box_hidden').html()
            });
        });

        //if (IsBrowserVersion({msie: 8, opera: 'none'}))
        //{
        if ($('table.pgui-grid.fixed-header').length > 0) {
            require(["jquery/jquery.fixedtableheader"], function() {
                if ($.browser.msie) {
                    $('table.grid th.row-selection').width('1%');
                }

                $('table.pgui-grid').fixedtableheader({
                    headerrowsize: 3,
                    top: 0//$('.navbar.navbar-fixed-top').height()
                });
            });
        }

        //}

        sc.initializeShortCuts($('body'));
    });
});