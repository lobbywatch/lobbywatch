{if !$Uploader->GetReadOnly()}

    {if $RenderText}
        {if $Uploader->GetShowImage() and !$HideImage}
            <img src="{$Uploader->GetLink()}">
            <br/>
        {/if}

        <br/>
        <div class="btn-group" data-toggle-name="{$Uploader->GetName()}_action" data-toggle="buttons-radio">
            <button type="button" value="Keep" class="active btn" data-toggle="button">{$Captions->GetMessageString('KeepImage')}</button>
            <button type="button" value="Remove" class="btn" data-toggle="button">{$Captions->GetMessageString('RemoveImage')}</button>
            <button id="{$Uploader->GetName()}-replace-image-button" type="button" value="Replace" class="btn" data-toggle="button">{$Captions->GetMessageString('ReplaceImage')}</button>
        </div>
        <input type="hidden" name="{$Uploader->GetName()}_action" value="Keep" />

        <br/>
        <div class="file-upload-control">
            <input
                type="file"
                name="{$Uploader->GetName()}_filename"
                {style_block} {$Uploader->GetCustomAttributes()} {/style_block}
                onchange="$('#{$Uploader->GetName()}-replace-image-button').click();">
        </div>
{/if}

{else}
{if $RenderText}
{if $Uploader->GetShowImage() and !$HideImage}
    <img src="{$Uploader->GetLink()}"><br/>
{else}
    <a class="image" target="_blank" title="download" href="{$Uploader->GetLink()|escapeurl}">Download file</a>
{/if}
{/if}

{/if}