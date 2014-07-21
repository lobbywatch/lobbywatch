define(function(require, exports, module) {

    var pc  = require('pgui.pagination'), 
        dtp = require('pgui.datetimepicker'), 
        Class = require('class'), 
        sc = require('pgui.shortcuts');

    $(function() {
        var $body = $('body');

        pc.setupPaginationControls($body);
        dtp.setupCalendarControls($body);

        $('[data-pg-typeahead=true]').each(function() {
            var typeHeadInput = $(this);

            require(['pgui.typeahead'], function(pt) {
                (new pt.PgTypeahead(typeHeadInput));
            })
        });

        require(['pgui.layout'], function(instance){
            instance.updatePopupHints($body);
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

        sc.initializeShortCuts($body);
    });
});