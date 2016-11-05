{if (HasHomePage() or count($navigation) > 1)}
    <ol class="breadcrumb pgui-breadcrumb">
        {if HasHomePage()}
            <li><a href="{php}echo GetHomeURL();{/php}"><i class="icon-home"></i></a></li>
        {/if}

        {foreach from=$navigation item=item name=navigation}
            <li class="dropdown">
                {if $item.url and not $smarty.foreach.navigation.last}
                    <a href="{$item.url}">{$item.title}</a>
                {else}
                    {$item.title}
                {/if}


                {if $item.siblings and count($item.siblings) > 0}
                    <button class="btn btn-xs btn-default dropdown-toggle pgui-breadcrumb-siblings" data-toggle="dropdown">
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        {foreach from=$item.siblings item=sibling}
                            <li><a href="{$sibling.url}">{$sibling.title}</a></li>
                        {/foreach}
                    </ul>
                {/if}
            </li>
        {/foreach}
    </ol>
{/if}