{strip}
{foreach item=item from=$messages}
    <div class="alert alert-{$type}{if !isset($dismissable) or $dismissable} alert-dismissable{/if}"{if $item.displayTime} data-display-time="{$item.displayTime}"{/if}>
        {if !isset($dismissable) or $dismissable}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        {/if}

        {if isset($caption) and $caption}
            <strong>{$caption}</strong><br>
        {/if}

        <div class="js-content">{$item.message}</div>
    </div>
{/foreach}
{/strip}