{if $TextEdit->getPrefix() and $TextEdit->getSuffix()}
    <div class="input-prepend input-append">
{elseif $TextEdit->getPrefix()}
    <div class="input-prepend">
{elseif $TextEdit->getSuffix()}
    <div class="input-append">
{/if}
{if $TextEdit->getPrefix()}
    <span class="add-on">{$TextEdit->getPrefix()}</span>
{/if}
<input
    {include file="editors/editor_options.tpl" Editor=$TextEdit}
    class="input-xlarge"
    value="{$TextEdit->GetHTMLValue()}"
    {if $TextEdit->getPlaceholder()}
        placeholder="{$TextEdit->getPlaceholder()}"
    {/if}
    {if $TextEdit->GetPasswordMode()}
        type="password"
    {else}
        type="text"
    {/if}
    {if $TextEdit->GetMaxLength()}
        maxlength="{$TextEdit->GetMaxLength()}"
    {/if}
    {if $TextEdit->GetSize()}
        size="{$TextEdit->GetSize()}"
    {/if}
    {style_block}
        width: auto;
    {/style_block}
>
{if $TextEdit->getSuffix()}
    <span class="add-on">{$TextEdit->getSuffix()}</span>
{/if}
{if $TextEdit->getPrefix() or $TextEdit->getSuffix()}
    </div>
{/if}
