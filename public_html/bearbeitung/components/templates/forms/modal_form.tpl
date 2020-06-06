<div class="modal-dialog {$modalSizeClass}">
    <div class="modal-content js-form-container">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{$Grid.Title}</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 js-form-collection">
                    {foreach from=$Forms item=Form name=forms}
                        {$Form}
                        {if not $smarty.foreach.forms.last}<hr>{/if}
                    {/foreach}
                </div>
            </div>

            {if $Grid.AllowAddMultipleRecords}
                <div class="row form-add-another-record">
                    <a href="#" class="js-form-add col-md-12{if $Grid.FormLayout->isHorizontal()} col-md-offset-3{/if}">
                        <span class="icon-plus"></span> {$Captions->GetMessageString('FormAdd')}
                    </a>
                </div>
            {/if}
        </div>

        <div class="modal-footer">
            <div class="btn-toolbar pull-right">

                <div class="btn-group">
                    <button class="btn btn-default" data-dismiss="modal" aria-label="Close">
                        {$Captions->GetMessageString('Cancel')}
                    </button>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary js-save js-primary-save">
                        {if $isMultiEditOperation}
                        {$Captions->GetMessageString('Update')}
                        {else}
                        {$Captions->GetMessageString('Save')}
                        {/if}
                    </button>
                    {if not $isNested && not $isMultiEditOperation}
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" class="js-save">{$Captions->GetMessageString('SaveAndBackToList')}</a></li>
                            <li><a href="#" class="js-save js-multiple-insert-hide" data-action="edit">{$Captions->GetMessageString('SaveAndEdit')}</a></li>
                            <li><a href="#" class="js-save js-save-insert" data-action="insert">{$Captions->GetMessageString('SaveAndInsert')}</a></li>

                            {if $Grid.Details and count($Grid.Details) > 0}
                                <li class="divider js-multiple-insert-hide"></li>
                            {/if}

                            {foreach from=$Grid.Details item=Detail name=Details}
                                <li><a class="js-save js-multiple-insert-hide" href="#" data-action="details" data-index="{$smarty.foreach.Details.index}">{$Detail.Caption|string_format:$Captions->GetMessageString('SaveAndOpenDetail')}</a></li>
                            {/foreach}
                        </ul>
                    {/if}
                </div>

            </div>
        </div>

        {include file='forms/form_scripts.tpl'}
    </div>
</div>
