{if $buttons.print_page or $buttons.print_all}
    <div class="btn-group">
        <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#" title="{$Captions->GetMessageString('Print')}">
            <i class="icon-print-page"></i>
            <span class="{$spanClasses}">{$Captions->GetMessageString('Print')}</span>
            <span class="caret"></span>
        </a>

        <ul class="dropdown-menu">
            {foreach from=$pageTitleButtons item=printButton key=name}
                {if $name == 'print_page' or $name == 'print_all'}
                    <li>
                        <a href="{$printButton.Href|escapeurl}"{$printButton.Target}>
                            <i class="{$printButton.IconClass}"></i>
                            {$printButton.Caption}
                        </a>
                    </li>
                {/if}
            {/foreach}
        </ul>
    </div>
{/if}