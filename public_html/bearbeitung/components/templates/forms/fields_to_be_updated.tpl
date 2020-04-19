<div class="row">
    <div id="form-group-fields-to-be-updated" class="form-group">
        <div class="form-group-label {if $Grid.FormLayout->isHorizontal()}col-sm-3{else}col-sm-12{/if}">
            <label class="control-label" for="fields-to-be-updated">
                {$Captions->GetMessageString('FieldsToBeUpdated')}
            </label>
        </div>
        <div class="{if $Grid.FormLayout->isHorizontal()}col-sm-9{else}col-sm-12{/if}">
            <div class="input-group">
                <div class="col-input" style="width:100%;max-width:100%">
                    <select id="fields-to-be-updated" name="fields_to_be_updated_edit[]" class="form-control" multiple data-editor="multivalue_select">
                        {foreach item=column from=$Grid.MultiEditColumns}
                        <option value="{$column->GetName()}"{if $Grid.AllFieldsToBeUpdatedByDefault} selected{/if}>{$column->GetCaption()}</option>
                        {/foreach}
                    </select>
                </div>
                <span id="clear-fields-to-be-updated" class="input-group-addon" title="{$Captions->GetMessageString('ClearFieldsToBeUpdated')}">
                    <span class="icon-remove"></span>
                </span>
            </div>
        </div>
        <div class="col-sm-12">
            <hr>
        </div>
    </div>
</div>
