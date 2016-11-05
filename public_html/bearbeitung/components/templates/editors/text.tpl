{if ($Editor->getPrefix() or $Editor->getSuffix())}
    <div class="input-group">
{/if}
{if $Editor->getPrefix()}
    <span class="input-group-addon">{$Editor->getPrefix()}</span>
{/if}
<input
    {include file="editors/editor_options.tpl" Editor=$Editor}
    class="form-control"
    value="{$Editor->GetHTMLValue()}"
    {if $Editor->getPlaceholder()}
        placeholder="{$Editor->getPlaceholder()}"
    {/if}
    {if $Editor->GetPasswordMode()}
        type="password"
    {else}
        type="text"
    {/if}
    {if $Editor->GetMaxLength()}
        maxlength="{$Editor->GetMaxLength()}"
    {/if}
>
{if $Editor->getSuffix()}
    <span class="input-group-addon">{$Editor->getSuffix()}</span>
{/if}
{if $Editor->getPrefix() or $Editor->getSuffix()}
    </div>
{/if}
