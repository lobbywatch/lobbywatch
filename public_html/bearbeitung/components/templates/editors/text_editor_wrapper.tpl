{if ($Editor->getPrefix() or $Editor->getSuffix())}
    <div class="input-group">
{/if}
{if $Editor->getPrefix()}
    <span class="input-group-addon">{$Editor->getPrefix()}</span>
{/if}

    {$TextEditorContent}

{if $Editor->getSuffix()}
    <span class="input-group-addon">{$Editor->getSuffix()}</span>
{/if}
{if $Editor->getPrefix() or $Editor->getSuffix()}
    </div>
{/if}
