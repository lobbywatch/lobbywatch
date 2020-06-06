{if $buttons.excel or $buttons.pdf or $buttons.csv or $buttons.xml or $buttons.word}
    <div class="btn-group">
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#" title="{$Captions->GetMessageString('Export')}">
            <i class="icon-export"></i>
            <span class="{$spanClasses}">{$Captions->GetMessageString('Export')}</span>
            <span class="caret"></span>
        </a>

        <ul class="dropdown-menu">
            {foreach from=$buttons item=Item key=Name}
                {if $Name != 'print_page' and $Name != 'print_all'}
                    {if $Item.BeginNewGroup}<li class="divider"></li>{/if}
                    <li><a href="{$Item.Href|escapeurl}"{$Item.Target}>
                            <i class="{$Item.IconClass}"></i>
                            {$Item.Caption}
                        </a></li>
                {/if}
            {/foreach}
        </ul>
    </div>
{/if}