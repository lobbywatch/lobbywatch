define(function(require, exports) {

    require('bootbox.min');
    var localizer = require('pgui.localizer').localizer;

    exports.setupModalEditors = function(context, grid) {
        if (!context)
            context = $(document);

        context.find('a[data-modal-delete=true]').each(function(index, button)
        {
            $(button).click(function(event)
            {
                event.preventDefault();
                bootbox.animate(false);
                bootbox.confirm(localizer.getString('DeleteRecordQuestion'), function(confirmed)
                    {
                        if (confirmed) {
                            var url = $(button).attr('href');
                            var handlerName = $(button).attr('data-delete-handler-name');

                            $.ajax({
                                url: url + "&hname=" + handlerName,
                                data: {},
                                success:
                                    function(data)
                                    {
                                        var response = $(data).find('response');
                                        if (response.find('type').text() == 'error')
                                        {
                                            bootbox.animate(false);
                                            bootbox.alert(response.find('error_message').text());
                                        }
                                        else
                                        {
                                            var rowToDelete = $(button).closest('tr');
                                            if (grid) {
                                                grid.removeRow(rowToDelete);
                                            }
                                        }
                                    },
                                dataType: 'xml',
                                error: function() {

                                }
                            });
                        }

                    });
            });
        });
    }
});

