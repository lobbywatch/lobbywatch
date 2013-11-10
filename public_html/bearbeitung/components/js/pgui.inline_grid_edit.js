function ExtractXmlDocumentFromFrame(frame) 
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
}

$.fn.sm_inline_grid_edit = function (a_options) {
    var _this = this;

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
        cancelControl.click(CancelEditingClickHandler)

        var commitControl = editControlsContainer.find(options.commitEditControl);
        commitControl.click(CommitClickHandler);

        HideCompleteEditControls(editControlsContainer);
    }

    function HideInitEditControls(editControlsContainer) {
        editControlsContainer.find(options.initEditControl).hide();
    }

    function ShowInitEditControls(editControlsContainer) {
        editControlsContainer.find(options.initEditControl).show();
    }

    function HideCompleteEditControls(editControlsContainer) {
        editControlsContainer.find(options.cancelEditControl).hide();
        editControlsContainer.find(options.commitEditControl).hide();
    }

    function ShowCompleteEditControls(editControlsContainer) {
        editControlsContainer.find(options.cancelEditControl).show();
        editControlsContainer.find(options.commitEditControl).show();
    }

    function CancelEditingClickHandler(event) {
        event.preventDefault();
        
        var cancelControl = $(this);
        var row = cancelControl.closest('tr');
        var editControlsContainer = cancelControl.closest(options.editControlsContainer);
        CancelInlineEditing(row, editControlsContainer);
    }

    function CommitClickHandler(event) {
        event.preventDefault();
        
        var commitControl = $(this);
        var row = commitControl.closest('tr');
        var editControlsContainer = commitControl.closest(options.editControlsContainer);
        CommitInlineEditing(row, editControlsContainer);
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
        form.append(editorHtml);
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

    function MoveRowInlineEditorsToForm(row, targetForm) {
        targetForm.append(row.find('td[data-inline-editing=true] div.inline_editor_container form > *'));
    }

    function SubmitFormWithTarget(form, target) {
        form.submit(function () {
            form.attr('target', target);
        });
        form.submit();
    }

    function CheckValidness(row)
    {
        var isAllControlsValid = true;
        /*row.find('form').each(function(index, form)
        {
            if (!$(form).valid())
                isAllControlsValid = false;
        });*/
        return isAllControlsValid;
    }

    function CommitInlineEditing(row, editControlsContainer) {
        var currentRow = row;

        if (!CheckValidness(row))
            return;

        var postForm = CreateFormForPostInlineEditors();
        postForm.append(editControlsContainer.find('input:hidden').clone());
        require(['pgui.controls'], function(ctrls) {
            ctrls.destroyEditors(currentRow);
        });
        MoveRowInlineEditorsToForm(currentRow, postForm);

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

        function ProcessResponce() {

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
                    setTimeout(ProcessResponce, 250);
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

            responseXml = ExtractXmlDocumentFromFrame(resultFrame[0]);

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
                    EnableValidation(row);                    
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
        setTimeout(ProcessResponce, 250);
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
        editControlsContainer.find(options.commitInsertControl).click(CommitInsertHandler);
    }

    function DeleteInlineInsertingControls(editControlsContainer) {
        var inlineInsertControls = editControlsContainer.find('span[data-content=inline_insert_controls]');
        inlineInsertControls.remove();
    }

    function CancelInsertHandler(event) {
        event.preventDefault();
        
        var row = $(this).closest('tr');
        row.remove();
        DestroyValidationErrorContainer(row.find(options.editControlsContainer));        
    }

    function CommitInsertHandler(event) {
        event.preventDefault();
        var row = $(this).closest('tr');
        var editControlsContainer = row.find(options.editControlsContainer);
        CommitInlineInserting(row, editControlsContainer);
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

    function CommitInlineInserting(row, editControlsContainer) {

        var currentRow = row;

        if (!CheckValidness(row))
            return;

        var postForm = CreateFormForPostInsertInlineEditors();
        postForm.append(editControlsContainer.find('input[type=hidden]').clone());
        require(['pgui.controls'], function(ctrls) {
            ctrls.destroyEditors(currentRow);
        });
        MoveRowInlineEditorsToForm(currentRow, postForm);

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
        function ProcessResponse() {

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
                    setTimeout(ProcessResponse, 250);
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


            var responseXml = ExtractXmlDocumentFromFrame(resultFrame[0]);

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
                EnableValidation(currentRow);
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
        setTimeout(ProcessResponse, 250);
    }

    function InlineAddHandler() {
        var templateRow = grid.find(options.newRecordRowTemplate);
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
                EnableValidation(row);
                
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
                EnableValidation(row);

                UnblockInterface();
            }
        );
    }

    function EnableValidation(row)
    {
        /*$(row).find('form').pgui_validate_form(
        {
            validate_errorClass: 'inline-edit-error',
            validate_errorPlacement: function(error, element)
            {
                relatedElement = element;
                if ($(element.attr('validation-error-label-related-control')).length > 0)
                    relatedElement = $(element.attr('validation-error-label-related-control'));
                var oldError = $(row.attr('error-container')).find('label[for=' + error.attr('for') + ']');

                if ((oldError.length > 0 && oldError.text() != error.text()) || (oldError.length == 0))
                {
                    if (oldError.length > 0)
                        oldError.closest("li").remove();
                    error.attr('for', element.attr('name'));
                    error.appendTo(row.attr('error-container'));
                    error.wrap("<li>");
                }
            },
            validate_success: function (error)
            {
                error.closest("li").remove();
            }            
        });*/
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

    return construct();
}
