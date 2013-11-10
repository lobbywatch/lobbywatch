<div class="btn-group export-button">


    {if $Items.excel or $Items.pdf or $Items.csv or $Items.xml or $Items.word}
    <a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
        <i class="pg-icon-export"></i>
            {$Captions->GetMessageString('Export')}
        <span class="caret"></span>
    </a>

    <ul class="dropdown-menu">
    {foreach from=$Items item=Item key=Name}
        {if $Name != 'print_page' and $Name != 'print_all'}
            {if $Item.BeginNewGroup}<li class="divider"></li>{/if}
            <li><a href="{$Item.Href|escapeurl}">
                <i class="pg-icon-{$Item.IconClass}"></i>
                {$Item.Caption}
            </a></li>
        {/if}
    {/foreach}
    </ul>
    {/if}


    {if $Items.print_page}
    <a class="btn" href="{$Items.print_page.Href|escapeurl}">
        <i class="pg-icon-{$Items.print_page.IconClass}"></i>
        {$Items.print_page.Caption}
    </a>
    {/if}

    {if $Items.print_all}
    <a class="btn" href="{$Items.print_all.Href|escapeurl}">
        <i class="pg-icon-{$Items.print_all.IconClass}"></i>
        {$Items.print_all.Caption}
    </a>
    {/if}

</div>