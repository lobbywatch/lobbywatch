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
                    <div class="label-container">
                        <label class="control-label" for="{$Column.FieldName}_edit">
                            {$Column.Caption}
                            {if $Column.Required}<span class="required-mark">*</span>{/if}
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


            });

        function EditForm_initd(editors) {
            {/literal}{$Grid.OnLoadScript}{literal};
        }
        {/literal}
    </script>
</div>

</div>