{php}
    $this->assign("IconByOperationMap", array('view' => 'icon-view', 'edit' => 'icon-edit', 'delete' => 'icon-remove', 'copy' => 'icon-copy' ));
{/php}

{assign var="UseOperationContainer" value=$UseOperationContainer|default:true}

{foreach item=Cell from=$Actions}

    {if $Cell.Data}
        {if $UseOperationContainer}<span data-column-name="{$Cell.OperationName}" class="operation-item">{/if}

            {if $Cell.Data.type == 'link'}

                <a href="{$Cell.Data.link}" title="{$Cell.Data.caption}"{$Cell.LinkTarget}{if $Cell.Data.useImage} class="link-icon"{/if}
                    {foreach from=$Cell.Data.additionalAttributes key=key item=value} {$key}="{$value}"{/foreach}>

                    {if $Cell.Data.useImage}
                        <i class="{$Cell.IconClass}"></i>
                    {else}
                        {$Cell.Data.caption}
                    {/if}

                </a>

            {elseif $Cell.Data.type == 'modal' or $Cell.Data.type == 'inline'}

                <a href="#" title="{$Cell.Data.dialogTitle}"{if $Cell.Data.useImage} class="link-icon"{/if} data-{$Cell.Data.type}-operation="{$Cell.Data.name}" data-content-link="{$Cell.Data.link}">

                    {if $Cell.Data.useImage}
                        <i class="{$IconByOperationMap[$Cell.OperationName]}"></i>
                    {else}
                        {$Cell.Data.caption}
                    {/if}

                </a>

            {else}

                {$Cell.Data}

            {/if}

        {if $UseOperationContainer}</span>{/if}
    {/if}

{/foreach}