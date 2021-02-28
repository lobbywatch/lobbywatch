{strip}
{foreach item=item from=$messages}
    <div class="alert alert-{$type} alert-dismissable"{if $item.displayTime} data-display-time="{$item.displayTime}"{/if}>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {if isset($caption) and $caption}
            <strong>{$caption}</strong><br>
        {/if}
        <div class="js-content">{$item.message}</div>
    </div>
{/foreach}
{/strip}
