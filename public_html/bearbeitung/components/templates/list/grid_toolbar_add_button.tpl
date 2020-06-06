{if $DataGrid.ActionsPanel.AddNewButton}
    <div class="btn-group">
        {if $DataGrid.ActionsPanel.AddNewButton eq 'modal' or $DataGrid.ActionsPanel.AddNewButton eq 'inline'}
            <button class="btn btn-default pgui-add"
                    data-content-link="{$DataGrid.Links.ModalInsertDialog|escapeurl}"
                    data-{$DataGrid.ActionsPanel.AddNewButton}-insert="true"
                    title="{$Captions->GetMessageString('AddNewRecord')}">
                <i class="icon-plus"></i>
                <span class="visible-lg-inline">{$Captions->GetMessageString('AddNewRecord')}</span>
            </button>
        {else}
            <a class="btn btn-default pgui-add" href="{$DataGrid.Links.SimpleAddNewRow|escapeurl}"
               title="{$Captions->GetMessageString('AddNewRecord')}">
                <i class="icon-plus"></i>
                <span class="visible-lg-inline">{$Captions->GetMessageString('AddNewRecord')}</span>
            </a>
        {/if}
        {if $DataGrid.AddNewChoices}
            <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                {foreach from=$DataGrid.AddNewChoices item=choice}
                    <li>
                        {if $DataGrid.ActionsPanel.AddNewButton eq 'modal'}
                            <a href="#"
                               data-modal-insert="true"
                               data-content-link="{$DataGrid.Links.ModalInsertDialog|escapeurl}&count={$choice}">
                                {$Captions->GetMessageString('AddMultipleRecords')|@sprintf:$choice}
                            </a>
                        {elseif $DataGrid.ActionsPanel.AddNewButton eq 'inline'}
                            <a href="#"
                               data-inline-insert="true"
                               data-content-link="{$DataGrid.Links.ModalInsertDialog|escapeurl}"
                               data-count="{$choice}">{$choice}</a>
                        {else}
                            <a href="{$DataGrid.Links.SimpleAddNewRow|escapeurl}&count={$choice}">
                                {$Captions->GetMessageString('AddMultipleRecords')|@sprintf:$choice}
                            </a>
                        {/if}
                    </li>
                {/foreach}
            </ul>
        {/if}
    </div>
{/if}
