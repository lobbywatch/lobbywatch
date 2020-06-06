{if !$Editor->GetReadOnly()}
    {if $Editor->GetShowImage()}
        <img src="{if $Editor->GetLink()}{$Editor->GetLink()}{else}#{/if}" style="{if !$Editor->GetLink()}display: none;{/if}max-width: 100%;{$Editor->getInlineStyles()}">
    {/if}

    <div style="margin: 1em 0;">
        <div class="btn-group" data-toggle-name="{$Editor->GetName()}_action" data-toggle="buttons-radio">
            {if $Editor->GetLink()}
            <button type="button" value="Keep" class="active btn btn-default" data-toggle="button">{$Captions->GetMessageString('KeepImage')}</button>
            {/if}
            <button type="button" value="Remove" class="btn btn-default" data-toggle="button">{$Captions->GetMessageString('RemoveImage')}</button>
            <button type="button" value="Replace" class="btn btn-default js-replace" data-toggle="button" disabled="disabled">{$Captions->GetMessageString('ReplaceImage')}</button>
        </div>
    </div>
    <input type="hidden" name="{$Editor->GetName()}_action" value="Keep" />

    <div class="file-upload-control">
        <input
            {if $id}id="{$id}"{/if}
            {$ViewData.Validators.InputAttributes}
            {if $Editor->GetLink()}data-has-file="true"{/if}
            data-editor="{$Editor->getEditorName()}"
            data-field-name="{$Editor->GetFieldName()}"
            type="file"
            name="{$Editor->GetName()}"
            {if $Editor->getAcceptableFileTypes()}
                accept="{$Editor->getAcceptableFileTypes()}"
            {/if}
            {if $Editor->getCustomAttributes()}
                {$Editor->getCustomAttributes()}
            {/if}>
    </div>
{else}
    {if $Editor->GetLink()}
        {if $Editor->GetShowImage()}
            <img src="{$Editor->GetLink()}" style="max-width: 100%;{$Editor->getInlineStyles()}"><br/>
        {else}
            <a class="image" target="_blank" title="download" href="{$Editor->GetLink()|escapeurl}">Download file</a>
        {/if}
    {else}
        <div class="form-control-static text-muted">{$Captions->GetMessageString('NoFile')}</div>
    {/if}
{/if}
