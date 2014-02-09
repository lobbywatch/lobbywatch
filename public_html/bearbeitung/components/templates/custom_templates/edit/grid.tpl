<div class="row-fluid">

<div class="page-header form-header">
    <h1>{$Grid.Title}</h1>
</div>

<div class="pgui-edit-form">
    <form class="form-horizontal"  enctype="multipart/form-data" method="POST" action="{$Grid.FormAction}">

        <div class="form-actions top-actions">

            <div class="btn-toolbar">
                <div class="btn-group">
                    <button class="btn btn-primary submit-button"
                            onclick="$(this).closest('form').submit(); return false;"
                            >{$Captions->GetMessageString('Save')}
                    </button>
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li data-value="save"><a href="#" class="save-button">{$Captions->GetMessageString('SaveAndBackToList')}</a></li>
                        <li data-value="saveedit"><a href="#" class="saveedit-button">{$Captions->GetMessageString('SaveAndEdit')}</a></li>
                        {if count($Grid.Details) > 0}
                            <li class="divider"></li>
                        {/if}
                        {foreach from=$Grid.Details item=Detail}
                            <li><a class="save-and-open-details" href="#" data-action="{$Detail.Link}">{$Detail.Caption|string_format:$Captions->GetMessageString('SaveAndOpenDetail')}</a></li>
                        {/foreach}
                    </ul>
                </div>

                <div class="btn-group">
                    <button class="btn" onclick="window.location.href='{$Grid.CancelUrl}'; return false;">{$Captions->GetMessageString('Cancel')}</button>
                </div>

            </div>
        </div>


        {if not $Grid.ErrorMessage eq ''}
            <div class="alert alert-error">
                <button class="close" data-dismiss="alert"><i class="icon-remove"></i></button>
                <strong>{$Captions->GetMessageString('ErrorsDuringUpdateProcess')}</strong>
                <br>
                {$Grid.ErrorMessage}
            </div>
        {/if}

        <fieldset>
            <input id="submit-action" name="submit1" type="hidden" value="save">
            {foreach key=HiddenValueName item=HiddenValue from=$HiddenValues}
                <input type="hidden" name="{$HiddenValueName}" value="{$HiddenValue}" />
            {/foreach}

            {foreach item=Column from=$Grid.Columns}
                <div class="control-group">
                    <div class="label-container"
                    {*data-hint="{$Hints[$Column.FieldName]}" data-comment="{$Column.FieldName}: {$Column.Hint}" title="{$Column.FieldName}: {$Column.Hint}" *}>
                        <label class="control-label" for="{$Column.FieldName}_edit">
                            <span {* if $Column.Hint}class="hint"{/if *}>{$Column.Caption}</span>
                            {if $Column.Required}<span class="required-mark">*</span>{/if}
                            {if $Column.Hint}<img src="img/icons/information.png" alt="Hinweis" data-hint="{$Column.Hint}" data-hinttitle="{$Column.Caption}">{/if}
                        </label>
                        {include file="edit_field_options.tpl" Column=$Column}
                    </div>
                    <div class="controls">
                        {$Column.Editor}
                    </div>
                </div>
            {/foreach}

            <div class="control-group">
                <div class="controls">
                    <span class="required-mark">*</span> - {$Captions->GetMessageString('RequiredField')}
                </div>
            </div>
        </fieldset>



            <div class="error-container"></div>

        <div class="form-actions">

            <div class="btn-toolbar">
                <div class="btn-group">
                    <button id="submit-button"
                            class="btn btn-primary submit-button"
                            onclick="$(this).closest('form').submit(); return false;"
                            >{$Captions->GetMessageString('Save')}
                    </button>
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li data-value="save"><a href="#" class="save-button">{$Captions->GetMessageString('SaveAndBackToList')}</a></li>
                        <li data-value="saveedit"><a href="#" class="saveedit-button">{$Captions->GetMessageString('SaveAndEdit')}</a></li>

                        {if count($Grid.Details) > 0}
                            <li class="divider"></li>
                        {/if}
                        {foreach from=$Grid.Details item=Detail}
                            <li><a class="save-and-open-details" href="#" data-action="{$Detail.Link}">{$Detail.Caption|string_format:$Captions->GetMessageString('SaveAndOpenDetail')}</a></li>
                        {/foreach}
                    </ul>
                </div>

                <div class="btn-group">
                    <button class="btn" onclick="window.location.href='{$Grid.CancelUrl}'; return false;">{$Captions->GetMessageString('Cancel')}</button>
                </div>

            </div>
        </div>

    </form>

    <script>
        {literal}
        $(function() {
                $('.save-and-open-details').click(function(e) {
                    e.preventDefault();
                    $('form').attr('action', $(this).attr('data-action'));
                    $('#submit-button').click();
                });

                $('.dropdown-toggle').dropdown();

                $('a.save-button').click(function() {
                    $('#submit-action').val('save');
                    $('#submit-button').click();
                });
                $('a.saveedit-button').click(function() {
                    $('#submit-action').val('saveedit');
                    $('#submit-button').click();
                });


               /**
                * In edit forms on Ctrl-S click the "save" action button.
                *
                * For Edit shortkey see in bergamotte-mgmt.js.
                */
                // Key codes: http://www.cambiaresearch.com/articles/15/javascript-char-codes-key-codes
                //console.log("Ctrl-S activated");
                $(window).keypress(function(event) {
                  //console.log( event );
                  if ((event.ctrlKey && event.which == 115) || (event.which == 19)) {
                    event.preventDefault();
                    //console.log("Save");
                    // Save is with Save as Draft module always at first position
                    $('#submit-action').val('save');
                    $('#submit-button').click();
                    // Do not trigger default behaviour of Firefox
                    // Ref: http://stackoverflow.com/questions/93695/best-cross-browser-method-to-capture-ctrls-with-jquery
                    return false;
                  }
                });

            });

        function EditForm_initd(editors) {
            {/literal}{$Grid.OnLoadScript}{literal};
        }

        {/literal}
    </script>
</div>

</div>
