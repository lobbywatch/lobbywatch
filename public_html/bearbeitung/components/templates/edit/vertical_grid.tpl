    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><i class="icon-remove"></i></button>
        <h3 class="title"></h3>
    </div>
    <div class="modal-body">

        <form class="pgui-edit-form form-horizontal" enctype="multipart/form-data" method="POST" action="{$Grid.FormAction}">
            <fieldset>
                <input type="hidden" name="edit_operation" value="commit" />
                <input id="submit-action" name="submit1" type="hidden" value="save">
            {foreach key=HiddenValueName item=HiddenValue from=$HiddenValues}
                <input type="hidden" name="{$HiddenValueName}" value="{$HiddenValue}" />
            {/foreach}

            {foreach item=Column from=$Grid.Columns}
                <div class="control-group">
                    <label class="control-label" for="not-existed">
                        {$Column.Caption}
                        {if $Column.Required}<span class="required-mark">*</span>{/if}
                    {include file="edit_field_options.tpl" Column=$Column}
                    </label>
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
        </form>

        <div class="error-container"></div>
    </div>

    <div class="modal-footer">

        <div class="btn-toolbar">

            <div class="btn-group">
                <button class="btn cancel-button">{$Captions->GetMessageString('Cancel')}</button>
            </div>

            <div class="btn-group">
                <button class="btn btn-primary submit-button">
                    {$Captions->GetMessageString('Save')}
                </button>
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li data-value="save"><a href="#" id="save">{$Captions->GetMessageString('SaveAndBackToList')}</a></li>
                    <li data-value="saveedit"><a href="#" id="saveedit">{$Captions->GetMessageString('SaveAndEdit')}</a></li>
                </ul>
            </div>
        </div>

    </div>
    <script type="text/javascript">
        {literal}
        function EditForm_initd(editors) {
        {/literal}{$Grid.OnLoadScript}{literal}
        }
        {/literal}
    </script>
