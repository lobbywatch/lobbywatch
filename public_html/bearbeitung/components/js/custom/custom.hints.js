define(function(require, exports, module) {

//    var Class               = require('class'),
//        fb                  = require('pgui.advanced_filter'),
//        localizer           = require('pgui.localizer').localizer,
//        events              = require('microevent'),
//        overlay             = require('pgui.overlay'),
//        async               = require('async'),
//        _                   = require('underscore'),
//        InputEvents         = require('pgui.events').InputEvents,
//        setupInputEvents    = require('pgui.events').setupInputEvents;

//    alert("custom.hints loaded");

    // Copied from pgui.grid.js
//     $("div, img").each(function() {
//       if ($(this).attr('data-hint')) {
//           // Ref: http://getbootstrap.com/javascript/#popovers
//           $(this).popover({
//               placement: 'right',
//               title: $(this).attr('data-hinttitle'),
//               content: $(this).attr('data-hint')
//           });
//       }
//     });
//     console.log('custom.hints init');
    $("div[data-hint], img[data-hint], th[data-hint]").each(function() {
//         console.log('custom.hints each');
        // http://getbootstrap.com/javascript/#tooltips
        // http://getbootstrap.com/javascript/#popovers
        $(this).popover({
            placement: 'right',
            container: 'body',
            trigger: 'hover',
            html: true,
            title: $(this).attr('data-hinttitle'),
            content: $(this).attr('data-hint')
        });
    });

//    var Grid = exports.Grid = Class.extend({
//
//        _bindHandlers: function() {
//            var self = this;
//
//
//            self.find('td').each(function() {
//                alert("self.find('td')");
//                if ($(this).attr('data-comment')) {
//                    $(this).popover({
//                        placement: 'top',
//                        title: $(this).attr('data-field-caption'),
//                        content: $(this).attr('data-comment')
//                    });
//                }
//            });
//
//        },
//
//    });

});
