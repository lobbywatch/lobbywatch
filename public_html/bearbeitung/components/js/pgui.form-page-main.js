define(['pgui.form_collection', 'pgui.shortcuts'], function(FormCollection, shortcuts) {
    return function () {
        var $formContainer = $('.js-form-container').first();
        var formCollection = new FormCollection(
            $formContainer,
            $('.js-form-collection'),
            $formContainer.data('form-url'),
            {
                done: function (hasErrors, responses, params) {
                    if (hasErrors) {
                        return true;
                    }

                    switch (params.action) {
                        case 'open':
                            location.href = params.url;
                            break;
                        case 'edit':
                            location.href = responses[0].editUrl;
                            break;
                        case 'details':
                            location.href = responses[0].details[params.index];
                            break;
                        default:
                            break;
                    }

                    return false;
                }
            }
        );
        shortcuts.push(['form']);
    }
});