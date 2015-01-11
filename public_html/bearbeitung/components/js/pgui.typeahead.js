define(function(require, exports) {

    var Class = require('class');

    exports.PgTypeahead = Class.extend({

        init: function(typeHeadInput) {

            typeHeadInput.focus(function(e) {

                typeHeadInput.data('typeahead').lookup(e, true);
            });

            typeHeadInput.blur(function(e) {
                typeHeadInput.removeClass('editing');
                typeHeadInput.data('pg-events').changed();
            });


            typeHeadInput.keydown(function(e) {
                typeHeadInput.attr('data-post-value', '');
            });

            typeHeadInput.keyup(function(e) {
                if (typeHeadInput.val() != '') {
                    typeHeadInput.addClass('editing');
                } else {
                    typeHeadInput.removeClass('editing');
                }
            });

            typeHeadInput.typeahead({
                property: "value",
                source: function(typehead, query, ignoreQuery) {
                    typeHeadInput.addClass('editing');
                    var url = location.protocol + '//' + location.host + location.pathname;
                    /*if (query == '') {
                     typehead.process([]);
                     return;
                     }*/
                    $.get(url, {
                        hname: typeHeadInput.attr('data-pg-typeahead-handler'),
                        term: query
                    }, function(data) {
                        typehead.process(data, ignoreQuery);
                    }, 'json');

                    return null;
                },
                onselect: function(val, e) {
                    typeHeadInput.attr('data-post-value', val.id);
                    typeHeadInput.removeClass('editing');
                    typeHeadInput.data('pg-events').changed();
                }
            });


        },

        getDisplayValue: function() {
        }
    });
});