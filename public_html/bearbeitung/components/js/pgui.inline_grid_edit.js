




function extractXmlDocumentFromFrame(frame)
{
        var doc = frame.contentWindow ?
        frame.contentWindow.document :
        frame.contentDocument ?
            frame.contentDocument :
            frame.document;

    if (doc == null)
        return null;
    if (doc.XMLDocument)
        return $(doc.XMLDocument);
    else
        return $(doc);
}

$.fn.sm_show_grid_error = function (a_options) {
    var _this = this;
    var grid;

    var defaults =
        {
            errorMessageHeader: 'Errors:',
            errorMessage: 'Some error message',
            columnSpan: 0,
            useBlockGUI: false
        };
    var options = $.extend(defaults, a_options);

    function ConstructErrorRow() {
        var row = $('<tr></tr>');

        var cell = $('<td></td>');
        cell.attr('colspan', options.columnSpan);
        cell.addClass('odd');
        cell.addClass('grid_error_row');

        var errorPanel = $('<div>');
        errorPanel.addClass('alert').addClass('alert-error');
        errorPanel.append('<strong>' + options.errorMessageHeader + '</strong><br>');
        errorPanel.append(options.errorMessage);

        var closeButton = $('<a data-dismiss="alert"><i class="icon-remove"></i></a>');
        closeButton.addClass('close');
        closeButton
            .click(RemoveErrorPanelHandler);

        errorPanel.prepend(closeButton);
        row.append(cell);
        cell.append(errorPanel);
        return row;
    }

    function RemoveErrorPanelHandler(event) {
        event.preventDefault();
        var errorRow = $(this).closest('tr');
        RemoveErrorPanel(errorRow);
    }

    function RemoveErrorPanel(errorRow) {
        var errorPanel = errorRow.find('div.grid_error_message');
        errorPanel.remove();
        errorRow.remove();
    }

    function construct() {
        grid = $(_this);

        grid.each(function () {
            $(this).find('> thead > tr.header').before(ConstructErrorRow());
        });
        return _this;
    }

    return construct();
};

$.fn.sm_inline_grid_edit = function (a_options) {
    var _this = this;
    var pguiValidation;
    require(['pgui.validation'],function(instance){
        pguiValidation = instance;
    });
    var defaults =
        {
            row: null,
            debug: false,
            editControlsContainer: '[data-column-name=InlineEdit]',
            initEditControl: 'a.inline_edit_init',
            cancelEditControl: 'a.inline_edit_cancel',
            commitEditControl: 'a.inline_edit_commit',
            cancelInsertControl: 'a.inline_insert_cancel',
            commitInsertControl: 'a.inline_insert_commit',
            requestAddress: '',
            useBlockGUI: true,
            inlineAddControl: '.inline_add_button',
            cancelButtonHint: 'Cancel',
            commitButtonHint: 'Commit',
            useImagesForActions: true,
            newRecordRowTemplate: '.new-record-row[data-new-row=false]',
            newRecordAfterRowTemplate: '.new-record-after-row[data-new-row=false]',
            responseCheckCount: 200,
            editingErrorMessageHeader: 'Errors:'
        };

    var options = $.extend(defaults, a_options);

    var grid;

    function ClearErrorRow() {
        var row = $('td.grid_error_row').closest('tr');
        row.remove();
    }


    function SetupEditControls(editControlsContainer) {
        var initEditControl = editControlsContainer.find(options.initEditControl);
        initEditControl.click(InlineEditHandler);

        var cancelControl = editControlsContainer.find(options.cancelEditControl);
        cancelControl.click(CancelEditingClickHandler);

        var commitControl = editControlsContainer.find(options.commitEditControl);
        commitControl.click(commitEditHandler);

        HideCompleteEditControls(editControlsContainer);
    }

    function ShowInitEditControls(editControlsContainer) {
        editControlsContainer.find(options.initEditControl).show();
    }

    function HideInitEditControls(editControlsContainer) {
        editControlsContainer.find(options.initEditControl).hide();
    }

    function HideCompleteEditControls(editControlsContainer) {
        editControlsContainer.find(options.cancelEditControl).hide();
        editControlsContainer.find(options.commitEditControl).hide();
    }

    function ShowCompleteEditControls(editControlsContainer) {
        editControlsContainer.find(options.cancelEditControl).show();
        editControlsContainer.find(options.commitEditControl).show();
    }

    function commitEditHandler(event) {
        event.preventDefault();
        var commitControl = $(this);
        var row = commitControl.closest('tr');
        var editControlsContainer = commitControl.closest(options.editControlsContainer);
        commit(row, editControlsContainer, 'edit');
    }

    function commitInlineEditing(row, editControlsContainer) {
        var currentRow = row;

        if (!checkValidness(row))
            return;

        var postForm = CreateFormForPostInlineEditors();
        postForm.append(editControlsContainer.find('input:hidden').clone());
        require(['pgui.controls'], function(ctrls) {
            ctrls.destroyEditors(currentRow);
        });

        var $originalEditors = moveRowInlineEditorsToForm(currentRow, postForm);


        /**
         *
         * @type {Object}
         */
        var legacyValidateForm = pguiValidation.Grid_ValidateForm(postForm, false);
        if (!legacyValidateForm.valid) {
            row.popover({
                placement: 'bottom',
                title: 'Validation error',
                content: legacyValidateForm.message
            });
            row.addClass('error');
            row.popover('show');
            return;
        }
        else {
            row.removeClass('error');
            row.popover('destroy');
            $originalEditors.remove();
        }

        var operationRandom = Math.floor(Math.random() * 100000);

        var resultFrame = $('<iframe name="inlineEditPostForm" id="inlineEditPostForm_' + operationRandom + '"></iframe>');
        resultFrame.attr('src', 'about:black');
        if (options.debug) {
            resultFrame.css('width', '1000px');
            resultFrame.css('height', '800px');
            resultFrame.css('border', '2px');
        }
        else {
            resultFrame.css('width', '0px');
            resultFrame.css('height', '0px');
            resultFrame.css('border', '0px');
        }
        resultFrame.css('padding', '0px');
        resultFrame.css('margin', '0px');

        $('body').append(postForm);
        $('body').append(resultFrame);

        BlockInterface();

        domCheckCount = options.responseCheckCount;

        function processResponse() {

            var io = resultFrame[0];
            try {
                doc = io.contentWindow ? io.contentWindow.document : io.contentDocument ? io.contentDocument : io.document
                var isXml = doc.XMLDocument || $.isXMLDoc(doc);
            }
            catch (e) {
                isXml = false;
            }

            if (!isXml) {
                if (--domCheckCount) {
                    setTimeout(processResponse, 250);
                    return;
                }

                if (!options.debug)
                    setInterval(function () { $('#inlineEditPostForm_' + operationRandom).remove(); }, 1000);
                $('#inline_edit_form').remove();

                currentRow.children('td[data-inline-editing=true]').each(function () {
                    var dataCell = $(this);
                    ReturnOldHtmlForCell(dataCell);
                });
                CompleteEditing(editControlsContainer);


                grid.sm_show_grid_error(
                    {
                        errorMessage: 'Response is not available',
                        columnSpan: currentRow.children('td').length,
                        errorMessageHeader: options.editingErrorMessageHeader
                    });

                UnblockInterface();
                return;
            }

            responseXml = extractXmlDocumentFromFrame(resultFrame[0]);

            if (responseXml.find('errormessage').length > 0) {
                grid.sm_show_grid_error(
                    {
                        errorMessage: responseXml.find('errormessage').text(),
                        columnSpan: currentRow.children('td').length,
                        errorMessageHeader: options.editingErrorMessageHeader
                    });

                responseXml.find('editor').each(function () {
                    var dataCell = currentRow.find('[data-column-name="' + $(this).attr('name') + '"]');

                    ReturnOldHtmlForCell(dataCell);
                    EmbedEditorFromXml(currentRow, $(this), false);

                    DestroyValidationErrorContainer(editControlsContainer);
                    CreateValidationErrorContainer(row, editControlsContainer);
                    enableValidation(row);

                });
            }
            else {
                CompleteEditing(editControlsContainer);

                responseXml.find('fieldvalue').each(function () {

                    var newValue = $(this).find('value').text();
                    var style = $(this).find('style');

                    var dataCell = currentRow.children('[data-column-name="' + $(this).attr('name') + '"]');
                    dataCell.attr('data-inline-editing', 'false');
                    if (style)
                        dataCell.attr('style', style.text());

                    dataCell.html('');
                    dataCell.append(newValue);

                    if (dataCell.is('[data-column-name=InlineEdit]'))
                    {
                        dataCell.closest('table').sm_inline_grid_edit(
                            {
                                row: currentRow,
                                useBlockGUI: true
                            });
                    }
                });
            }
            UnblockInterface();

            if (!options.debug)
                setInterval(function () { $('#inlineEditPostForm_' + operationRandom).remove(); }, 1000);
            $('#inline_edit_form').remove();
        }

        setTimeout(function () { SubmitFormWithTarget(postForm, 'inlineEditPostForm'); }, 100);
        setTimeout(processResponse, 250);
    }

    function commitInsertHandler(event) {
        event.preventDefault();
        var row = $(this).closest('tr');
        var editControlsContainer = row.find(options.editControlsContainer);
        commit(row, editControlsContainer, 'insert');
    }

    function commitInlineInserting(row, editControlsContainer) {

        var currentRow = row;

        if (!checkValidness(row))
            return;

        var postForm = CreateFormForPostInsertInlineEditors();
        postForm.append(editControlsContainer.find('input[type=hidden]').clone());
        require(['pgui.controls'], function(ctrls) {
            ctrls.destroyEditors(currentRow);
        });
        moveRowInlineEditorsToForm(currentRow, postForm);

        var operationRandom = Math.floor(Math.random() * 100000);

        var resultFrame = $('<iframe name="inlineEditPostForm" id="inlineEditPostForm_' + operationRandom + '"></iframe>');
        resultFrame.attr('src', 'about:black');
        if (options.debug) {
            resultFrame.css('width', '1000px');
            resultFrame.css('height', '800px');
            resultFrame.css('border', '2px');
        }
        else {
            resultFrame.css('width', '0px');
            resultFrame.css('height', '0px');
            resultFrame.css('border', '0px');
        }
        resultFrame.css('padding', '0px');
        resultFrame.css('margin', '0px');

        $('body').append(postForm);
        $('body').append(resultFrame);

        BlockInterface();

        domCheckCount = options.responseCheckCount;
        function processResponse() {

            io = resultFrame[0];
            try {
                doc = io.contentWindow ? io.contentWindow.document : io.contentDocument ? io.contentDocument : io.document
                var isXml = doc.XMLDocument || $.isXMLDoc(doc);
            }
            catch (e) {
                isXml = false;
            }

            if (!isXml) {
                if (--domCheckCount) {
                    setTimeout(processResponse, 250);
                    return;
                }

                if (!options.debug)
                    setInterval(function () { $('#inlineEditPostForm_' + operationRandom).remove(); }, 1000);
                $('#inline_edit_form').remove();

                currentRow.next().remove();
                currentRow.remove();

                grid.sm_show_grid_error(
                    {
                        errorMessage: 'Response is not available',
                        columnSpan: currentRow.children('td').length,
                        errorMessageHeader: options.editingErrorMessageHeader
                    });

                DestroyValidationErrorContainer(editControlsContainer);

                UnblockInterface();
                return;
            }


            var responseXml = extractXmlDocumentFromFrame(resultFrame[0]);

            if (responseXml.find('errormessage').length > 0) {
                grid.sm_show_grid_error(
                    {
                        errorMessage: responseXml.find('errormessage').text(),
                        columnSpan: currentRow.children('td').length,
                        errorMessageHeader: options.editingErrorMessageHeader
                    });

                responseXml.find('editor').each(function () {
                    var dataCell = currentRow.find('[data-column-name="' + $(this).attr('name') + '"]');
                    ReturnOldHtmlForCell(dataCell);
                    EmbedEditorFromXml(currentRow, $(this), true);
                });

                DestroyValidationErrorContainer(editControlsContainer);
                CreateValidationErrorContainer(currentRow, editControlsContainer);
                enableValidation(currentRow);
            }
            else {
                grid.find('.emplygrid').remove();
                DeleteInlineInsertingControls(editControlsContainer);

                responseXml.find('fieldvalue').each(function () {
                    var newValue = $(this).find('value').text();
                    var afterRow = $(this).find('afterrowcontrol').text();
                    var cellStyle = $(this).find('style');
                    var dataCell = currentRow.children('[data-column-name="' + $(this).attr('name') + '"]');
                    dataCell.attr('data-inline-editing', 'false');

                    if (cellStyle)
                        dataCell.attr('style', cellStyle.text());

                    dataCell.html('');
                    dataCell.append(newValue);
                    currentRow.next().find('td').append(afterRow);
                });

                currentRow.removeAttr('id');
                currentRow.addClass('pg-row');

                //RecalculateGridLineNumbers();
                DestroyValidationErrorContainer(editControlsContainer);
                SetupEditControls(editControlsContainer);
                ClearErrorRow();
            }

            UnblockInterface();

            if (!options.debug)
                setInterval(function () { $('#inlineEditPostForm_' + operationRandom).remove(); }, 1000);
            $('#inline_edit_form').remove();
        }

        setTimeout(function () { SubmitFormWithTarget(postForm, 'inlineEditPostForm'); }, 100);
        setTimeout(processResponse, 250);
    }

    function CompleteEditing(editControlsContainer) {
        ShowInitEditControls(editControlsContainer);
        HideCompleteEditControls(editControlsContainer);
        DestroyValidationErrorContainer(editControlsContainer);
        ClearErrorRow();

        require(['pgui.controls'], function(ctrls) {
            ctrls.destroyEditors(editControlsContainer);
        });

    }

    function ReturnOldHtmlForCell(dataCell) {
        var oldHtml = dataCell.children('div.phpgen-ui-inline-edit-cell-old-data').html();
        dataCell.html(oldHtml);
    }

    function EmbedEditorFromXml(row, editorXmlElement, isInsert) {
        var editorHtml = editorXmlElement.find('html').text();
        var editorScript = editorXmlElement.find('script').text();
        var editorFieldName = editorXmlElement.attr('name');

        var dataCell = row.find('[data-column-name="' + editorFieldName + '"]');
        dataCell.attr('data-inline-editing', 'true');

        var inlineEditorContainer = $('<div>');
        inlineEditorContainer.addClass('inline_editor_container');
        var form = $('<form>').addClass('inline-edit-editor-form');
        var idRandom = Math.floor(Math.random() * 100000);
        var formId = 'inline-edit-editor-form_' + idRandom;
        form.attr('id', formId);

        var $controlGroup = $('<div>');
        $controlGroup.addClass('control-group');
        $controlGroup.attr('data-parent-form-id', idRandom);
        $controlGroup.append(editorHtml);

        form.append($controlGroup);
        inlineEditorContainer.append(form);

        var oldHtmlContainer = $('<div>');
        oldHtmlContainer.addClass('phpgen-ui-inline-edit-cell-old-data');
        oldHtmlContainer.css('display', 'none');
        oldHtmlContainer.append(dataCell.html());

        dataCell.html('');
        dataCell.append(inlineEditorContainer);
        dataCell.append(oldHtmlContainer);

        require(['pgui.controls'], function(ctrls) {
            ctrls.initEditors(dataCell);
        });

        require(['pgui.forms'], function (forms) {
            new forms.EditForm(dataCell);
        });

        try {
            eval(editorScript);
        }
        catch (e)
        { }
    }

    function CancelInlineEditing(currentRow, editControlsContainer) {
        currentRow.children('td[data-inline-editing=true]').each(function () {
            var dataCell = $(this);
            ReturnOldHtmlForCell(dataCell);
        });
        CompleteEditing(editControlsContainer);
    }

    function BlockInterface() {
        return;

        if (!options.debug && options.useBlockGUI)
        {
            require([PhpGen.Module.UIBlock], function()
            {
                $.blockUI(
                {
                    message: '<span class="wait_message">Please wait...</span>',
                    overlayCSS:
                    {
                        backgroundColor: '#fff'
                    },
                    css:
                    {
                        border: 'no',
                        padding: '15px',
                        backgroundColor: '#aaa',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#000'
                    }
                });
            });
        }
    }

    function UnblockInterface() {
        return;
        if (!options.debug && options.useBlockGUI)
        {
            require([PhpGen.Module.UIBlock], function()
            {
                $.unblockUI();
            });
        }
    }

    /**
     * @param {jQuery} $row Table row containing editors
     * @param {jQuery} $targetForm Form to paste editors to
     * @return {jQuery} Original editors
     */
    function moveRowInlineEditorsToForm($row, $targetForm) {

        var $editors = $row.find('td[data-inline-editing=true] div.inline_editor_container form > *');

        /**
         * This is fix for jQuery clone value issue
         * @TODO place this in the module definition wrapper after jquery appears listed as dependency
         */

        (function (original) {
            jQuery.fn.clone = function () {

                var valuesIssueElements = ['textarea', 'select'],
                    issueFieldsSelector = valuesIssueElements.join(','),
                    result = original.apply (this, arguments),
                    $originalElements = this.find(issueFieldsSelector),
                    $resultElements = result.find(issueFieldsSelector);

                for (var i = 0, l = $originalElements.length; i < l; ++i){
                    $($resultElements[i]).val ($($originalElements[i]).val());
                }


                return result;
            };
        }) ($.fn.clone);

        $targetForm.append($editors.clone());

        return $editors;
    }

    function SubmitFormWithTarget(form, target) {
        form.submit(function () {
            form.attr('target', target);
        });
        form.submit();
    }

    /**
     * Validate inline row controls
     * @param row
     * @returns {boolean}
     */
    function checkValidness(row)
    {
        var isAllControlsValid = true;
        row.find('form').each(function(index, form)
        {
            if (!$(form).valid())
                isAllControlsValid = false;
        });
        return isAllControlsValid;
    }


    function GetEditorsNameSuffix(responseXml) {
        return $(responseXml).find('namesuffix').text();
    }

    function CreateInlineInsertingControls(editControlsContainer) {
        if (options.useImagesForActions) {
            editControlsContainer.append(
            '<span style="white-space: nowrap;" data-content="inline_insert_controls">' +
            '<a href="#" class="inline_insert_cancel" title="' + options.cancelButtonHint + '"><i class="pg-icon-inline-edit-cancel" alt="' + options.cancelButtonHint + '"></i></a>' +
            '<a href="#" class="inline_insert_commit" title="' + options.commitButtonHint + '"><i class="pg-icon-inline-edit-commit" alt="' + options.commitButtonHint + '"></i></a>' +
            '</span>');
        }
        else {
            editControlsContainer.append(
            '<span style="white-space: nowrap;" data-content="inline_insert_controls">' +
            '<a href="#" style="margin-right: 5px;" class="inline_insert_cancel" title="' + options.cancelButtonHint + '">' + options.cancelButtonHint + '</a>' +
            '<a href="#" class="inline_insert_commit" title="' + options.commitButtonHint + '">' + options.commitButtonHint + '</a>' +
            '</span>');
        }
        editControlsContainer.find(options.cancelInsertControl).click(CancelInsertHandler);
        editControlsContainer.find(options.commitInsertControl).click(commitInsertHandler);
    }

    function DeleteInlineInsertingControls(editControlsContainer) {
        var inlineInsertControls = editControlsContainer.find('span[data-content=inline_insert_controls]');
        inlineInsertControls.remove();
    }

    function CancelEditingClickHandler(event) {
        event.preventDefault();

        var cancelControl = $(this);
        var $row = cancelControl.closest('tr');
        var editControlsContainer = cancelControl.closest(options.editControlsContainer);
        clearRowError($row);
        CancelInlineEditing($row, editControlsContainer);
    }


    function CancelInsertHandler(event) {
        event.preventDefault();
        
        var $row = $(this).closest('tr');
        clearRowError($row);
        $row.remove();
        // DestroyValidationErrorContainer(row.find(options.editControlsContainer));
    }


    function CreateFormForPostInsertInlineEditors() {
        var postForm = $('<form>');
        postForm.attr('id', 'inline_edit_form');
        postForm.attr('enctype', 'multipart/form-data');
        postForm.attr('action', options.requestAddress);
        postForm.attr('method', 'POST');
        postForm.css('display', 'none');

        postForm.append('<input type="hidden" name="operation" value="arqiic" />');
        return postForm;
    }

    /**
     *
     * @returns {*|jQuery|HTMLElement}
     * @constructor
     */
    function CreateFormForPostInlineEditors() {
        var postForm = $('<form>');
        postForm.attr('id', 'inline_edit_form');
        postForm.attr('enctype', 'multipart/form-data');
        postForm.attr('action', options.requestAddress);
        postForm.attr('method', 'POST');
        postForm.css('display', 'none');

        postForm.append('<input type="hidden" name="operation" value="arqiec" />');
        return postForm;
    }

    function InlineAddHandler() {
        var templateRow = grid.find(options.newRecordRowTemplate).first();
        var templateAfterRow = grid.find(options.newRecordAfterRowTemplate);

        var row = templateRow.clone();
        var afterRow = templateAfterRow.clone();

        row.attr('data-new-row', 'true');
        afterRow.attr('data-new-row', 'true');

        templateRow.before(row);
        row.after(afterRow);

        var editControlsContainer = row.find(options.editControlsContainer);

        var requestData = {};
        requestData['operation'] = 'arqii';

        BlockInterface();

        $.get(
            options.requestAddress,
            requestData,
            function ready(data) {
                row.css('display', '');
                afterRow.css('display', '');
                CreateInlineInsertingControls(editControlsContainer);

                // HideInitEditControls(editControlsContainer);
                // ShowCompleteEditControls(editControlsContainer);

                var nameSuffixInput = $('<input name="namesuffix" type="hidden">');
                nameSuffixInput.val(GetEditorsNameSuffix(data));

                editControlsContainer.append(nameSuffixInput);

                $(data).find('editor').each(function () {
                    EmbedEditorFromXml(row, $(this), true);
                });

                CreateValidationErrorContainer(row, editControlsContainer);
                enableValidation(row);
                
                UnblockInterface();
            }
        );
    }

    function InlineEditHandler(event) {
        event.preventDefault();

        var row = $(this).closest('tr');
        var editControlsContainer = $(this).closest(options.editControlsContainer);

        BlockInterface();

        var requestData = {};
        editControlsContainer.find('input[type=hidden]').each(function () {
            requestData[$(this).attr('name')] = $(this).val();
        });
        requestData['operation'] = 'arqie';

        $.get(
            options.requestAddress,
            requestData,
            function ready(data) {
                HideInitEditControls(editControlsContainer);
                ShowCompleteEditControls(editControlsContainer);

                var nameSuffixInput = $('<input name="namesuffix" type="hidden">');
                nameSuffixInput.val(GetEditorsNameSuffix(data));
                editControlsContainer.append(nameSuffixInput);

                $(data).find('editor').each(function () {
                    try {
                        EmbedEditorFromXml(row, $(this), false);
                    }
                    catch(e) {
                        alert(e);
                    }
                });

                CreateValidationErrorContainer(row, editControlsContainer);
                enableValidation(row);

                UnblockInterface();
            }
        );
    }

    function enableValidation(row)
    {
        /**
        * @var {string} errorMessageTitle The title message of validation error popover box
        */
        var errorMessageTitle;

        /**
         * @function unHighlightHandler Handler to override pgui_validate_form unhighlight method via config
         * @param {HTMLElement} element
         */
        function unHighlightHandler(element) {
            /**
             * @var {jQuery} Wrapped element
             */
            var $elementToValidate;
            $elementToValidate = $(element);
            $elementToValidate.closest('.control-group').removeClass('error');
            $elementToValidate.popover('destroy');
        }

        errorMessageTitle = 'Validation error';

        $(row).find('form').pgui_validate_form(
        {
            validate_errorClass: 'inline-edit-error',
            validate_errorPlacement: function(error, element)
            {
                if (error.text()) {
                    element.popover({
                        placement: 'bottom',
                        title: errorMessageTitle,
                        content: error.text()
                    });
                }
            },
            validate_success: undefined,
            highlight: function(element, errorClass, validClass) {
                /**
                 * @var {jQuery} Wrapped element
                 */
                var $elementToValidate;
                unHighlightHandler(element);
                $elementToValidate = $(element);
                /**
                 * probably debugging output
                 */
                //console.log($elementToValidate.closest('.control-group'));
                /**
                 * This is not a solution. Some times appending error to class to closest control-group is just not enough.
                 * For example typeahead.
                 * @TODO Refactor
                 */
                $elementToValidate.closest('.control-group').addClass('error');
            },
            unhighlight: unHighlightHandler
        });
    }

    function DestroyValidationErrorContainer(editControlsContainer)
    {
        $(editControlsContainer.closest('tr').attr('error-container')).remove();        
    }

    function CreateValidationErrorContainer(row, editControlsContainer)
    {
        var errorContainerId = 'error-box' + Math.floor(Math.random() * 100000);
        row.attr('error-container', '#' + errorContainerId);

        var errorBox = $("<ul>");
        
        errorBox.addClass('inline-editing-error-box');
        errorBox.attr('id', errorContainerId);
        errorBox.css('position', 'absolute');

        $('body').append(errorBox);


        errorBox.offset( {
            top: editControlsContainer.offset().top + editControlsContainer.outerHeight(),
            left: editControlsContainer.offset().left
        });

    }

    function construct() {
        grid = $(_this);

        if (options.row != null)
        {
            editControlsContainers = options.row.find(options.editControlsContainer);
            editControlsContainers.each(function () {
                SetupEditControls($(this));
            });
        }
        else
        {
            var inlineAddButton = grid.find(options.inlineAddControl);
            inlineAddButton.click(InlineAddHandler);

            editControlsContainers = grid.find('tr').find(options.editControlsContainer);
            editControlsContainers.each(function () {
                SetupEditControls($(this));
            });
        }
        //
        return _this;
    }

    /**
     * @TODO This method should be refactored to SRP. It has too many tasks to do
     * @function commit Commit inline data
     * @param {jQuery} row
     * @param {jQuery} editControlsContainer
     * @param {string} commitOperationTypeName
     * @throws {Error}
     */
    function commit(row, editControlsContainer, commitOperationTypeName) {
        /**
         * Current committing row
         * @type {jQuery}
         */
        var currentRow = row;

        /**
         * Validate each field in row and
         * If one of row fields is invalid then there is nothing else to do return
         */
        if (!checkValidness(currentRow)) return;

        /**
         * Handlers for post form creation operation
         * @type {{edit: CreateFormForPostInlineEditors, insert: CreateFormForPostInsertInlineEditors}}
         */
        var postOperationMakeFormHandlers = {
            'edit': CreateFormForPostInlineEditors,
            'insert': CreateFormForPostInsertInlineEditors
        };

        /**
         * Make new form for post to the server
         * @type {jQuery}
         */
        var postForm = postOperationMakeFormHandlers[commitOperationTypeName]();

        /**
         * The edit and insert operations controls selectors
         * @type {{}}
         */
        var commitOperationControlsSelectors = {
            edit: 'input:hidden',
            insert: 'input[type=hidden]'
        };
        /**
         * append fields to the post form
         */
        postForm.append(editControlsContainer.find(commitOperationControlsSelectors[commitOperationTypeName]).clone());

        require(['pgui.controls'], function(ctrls) {
            ctrls.destroyEditors(currentRow);
        });

        var $inlineEditorContainers = currentRow.find('div.control-group');
        $inlineEditorContainers.detach().appendTo(postForm);

        function moveInlineEditorContainersToTheirParentForms() {
            $inlineEditorContainers.each(function () {
                var formId = $(this).attr('data-parent-form-id');
                var $parentForm = $('#inline-edit-editor-form_' + formId);
                $(this).detach().appendTo($parentForm);
            });
        }

        /**
         * Form level validation
         * @type {Object}
         */

        var legacyValidateForm = pguiValidation.Grid_ValidateForm(postForm, commitOperationTypeName === 'insert');
        if (legacyValidateForm.valid) {
            clearRowError(currentRow);
        }
        else {
            moveInlineEditorContainersToTheirParentForms();
            addRowError(currentRow, legacyValidateForm.message);
            return;
        }

        var operationRandom = Math.floor(Math.random() * 100000);

        /**
         * Iframe
         * @type {*|jQuery|HTMLElement}
         */
        var $resultFrame = $('<iframe/>', {
            id: 'inlineEditPostForm_' + operationRandom,
            name: 'inlineEditPostForm',
            src: 'about:black'
        });

        /**
         * The result frame css styles
         * @type {{width: string, height: string, border: string}}
         */
        var resultFrameCss = {
            width: '0px',
            height: '0px',
            border: 'opx',
            margin: '0px',
            padding: '0px'
        };

        if (options.debug) {
            resultFrameCss.width = '1000px';
            resultFrameCss.height = '800px';
            resultFrameCss.border = '2px';
        }

        $resultFrame.css(resultFrameCss);

        /**
         * Entire body object
         * @type {*|jQuery|HTMLElement}
         */
        var $body = $('body');

        $body.append(postForm);
        $body.append($resultFrame);

        BlockInterface();
        /**
         * @TODO refactor to local variable
         * @type {number}
         */
        domCheckCount = options.responseCheckCount;

        function processResponse() {
            /**
             *
             */
            var io = $resultFrame[0];

            /**
             * Some sort of a edit form
             * @type {*|jQuery|HTMLElement}
             */
            var $inline_edit_form = $('#inline_edit_form');

            try {
                /**
                 * @TODO refactor to local variable
                 */
                doc = io.contentWindow ? io.contentWindow.document : io.contentDocument ? io.contentDocument : io.document
                var isXml = doc.XMLDocument || $.isXMLDoc(doc);
            }
            catch (e) {
                isXml = false;
            }

            if (!isXml) {
                if (--domCheckCount) {
                    setTimeout(processResponse, 250);
                    return;
                }

                if (!options.debug)
                    setInterval(function () { $('#inlineEditPostForm_' + operationRandom).remove(); }, 1000);



                $inline_edit_form.remove();

                if (commitOperationTypeName === 'edit') {
                    currentRow.children('td[data-inline-editing=true]').each(function () {
                        var dataCell = $(this);
                        ReturnOldHtmlForCell(dataCell);
                    });
                    CompleteEditing(editControlsContainer);
                } else if (commitOperationTypeName === 'insert') {
                    currentRow.next().remove();
                    currentRow.remove();
                }

                grid.sm_show_grid_error(
                    {
                        errorMessage: 'Response is not available',
                        columnSpan: currentRow.children('td').length,
                        errorMessageHeader: options.editingErrorMessageHeader
                    });
                if (commitOperationTypeName === 'insert') {DestroyValidationErrorContainer(editControlsContainer);}
                UnblockInterface();
                return;
            }
            /**
             * @TODO refactor to local variable
             */
            responseXml = extractXmlDocumentFromFrame($resultFrame[0]);
            
            if (responseXml.find('errormessage').length > 0) {
                grid.sm_show_grid_error(
                    {
                        errorMessage: responseXml.find('errormessage').text(),
                        columnSpan: currentRow.children('td').length,
                        errorMessageHeader: options.editingErrorMessageHeader
                    });

                moveInlineEditorContainersToTheirParentForms();
            }
            else {

                if (commitOperationTypeName === 'edit') {
                    CompleteEditing(editControlsContainer);
                } else if (commitOperationTypeName === 'insert') {
                    grid.find('.emplygrid').remove();
                    DeleteInlineInsertingControls(editControlsContainer);
                }

                responseXml.find('fieldvalue').each(function () {

                    var newValue = $(this).find('value').text();

                    if (commitOperationTypeName === 'edit'){
                        var afterRow = $(this).find('afterrowcontrol').text();
                        currentRow.next().find('td').append(afterRow);
                    }

                    var style = $(this).find('style');
                    var dataCell = currentRow.children('[data-column-name="' + $(this).attr('name') + '"]');
                    dataCell.attr('data-inline-editing', 'false');

                    if (style)
                        dataCell.attr('style', style.text());

                    dataCell.html('');
                    dataCell.append(newValue);

                    if (commitOperationTypeName === 'edit') {
                        if (dataCell.is('[data-column-name=InlineEdit]')) {
                            dataCell.closest('table').sm_inline_grid_edit(
                                {
                                    row: currentRow,
                                    useBlockGUI: true,
                                    requestAddress: options.requestAddress
                                });
                        }
                    }


                });


                if (commitOperationTypeName === 'insert') {

                    currentRow.removeAttr('id');
                    currentRow.removeClass('new-record-row');
                    currentRow.addClass('pg-row');
                    SetupEditControls(editControlsContainer);
                }

            }

            UnblockInterface();

            if (!options.debug)
                setInterval(function () { $('#inlineEditPostForm_' + operationRandom).remove(); }, 1000);
            $inline_edit_form.remove();
        }

        /**
         * Something strange going on here
         * @TODO figure it out and refactor
         */
        setTimeout(function () {
            SubmitFormWithTarget(postForm, 'inlineEditPostForm');
        }, 100);

        setTimeout(processResponse, 250);

    }

    /**
     * @function clearRowError Clear row error messages
     * @param {jQuery} $row Table row
     */
    function clearRowError ($row) {
        $row.removeClass('error');
        $row.popover('destroy');
        $('.popover').remove();

    }

    /**
     * @function Add row error messages
     * @param {jQuery} $row Table row
     * @param  {string} errorMessage Message to fill in to popover content
     */
    function addRowError ($row, errorMessage) {
        clearRowError($row);

        $row.popover({
            placement: 'bottom',
            title: 'Validation error',
            content: errorMessage
        });
        $row.popover('show');
        $row.addClass('error');
    }

    return construct();
};
