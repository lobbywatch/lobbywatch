{if !$Editor->GetReadOnly()}
    {if $RenderText}
        {if $Editor->GetShowImage() and !$HideImage and $Editor->GetLink()}
            <img src="{$Editor->GetLink()}" style="max-width: 100%;{$Editor->getInlineStyles()}">
        {/if}

        <div style="margin: 1em 0;">
            <div class="btn-group" data-toggle-name="{$Editor->GetName()}_action" data-toggle="buttons-radio">
                <button type="button" value="Keep" class="active btn btn-default" data-toggle="button">{$Captions->GetMessageString('KeepImage')}</button>
                <button type="button" value="Remove" class="btn btn-default" data-toggle="button">{$Captions->GetMessageString('RemoveImage')}</button>
                <button type="button" value="Replace" class="btn btn-default" data-toggle="button">{$Captions->GetMessageString('ReplaceImage')}</button>
            </div>
        </div>
        <input type="hidden" name="{$Editor->GetName()}_action" value="Keep" />

        <div class="file-upload-control">
            <input
                {if $id}id="{$id}"{/if}
                {$Validators.InputAttributes}
                {if $Editor->GetLink()}data-has-file="true"{/if}
                data-editor="{$Editor->getEditorName()}"
                data-field-name="{$Editor->GetName()}"
                type="file"
                name="{$Editor->GetName()}_filename"
                {style_block} {$Editor->GetCustomAttributes()} {/style_block}>
        </div>
    {/if}
{elseif $RenderText}
    {if $Editor->GetLink()}
        {if $Editor->GetShowImage() and !$HideImage}
            <img src="{$Editor->GetLink()}"><br/>
        {else}
            <a class="image" target="_blank" title="download" href="{$Editor->GetLink()|escapeurl}">Download file</a>
        {/if}
    {else}
        <div class="form-control-static text-muted">{$Captions->GetMessageString('NoFile')}</div>
    {/if}
{/if}
