{capture assign='ActionsContent'}

    <div class="btn-group">
        <button type="submit" class="btn btn-primary js-save js-primary-save" data-action="open" data-url="{$Grid.CancelUrl}">
            {if $isMultiEditOperation}
                {$Captions->GetMessageString('Update')}
            {else}
                {$Captions->GetMessageString('Save')}
            {/if}
        </button>
        {if not $isMultiEditOperation}
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="#" class="js-save" data-action="open" data-url="{$Grid.CancelUrl}">{$Captions->GetMessageString('SaveAndBackToList')}</a></li>
            <li><a href="#" class="js-save js-multiple-insert-hide" data-action="edit">{$Captions->GetMessageString('SaveAndEdit')}</a></li>
            <li><a href="#" class="js-save js-save-insert" data-action="open" data-url="{$Grid.InsertUrl}">{$Captions->GetMessageString('SaveAndInsert')}</a></li>

            {if $Grid.Details and count($Grid.Details) > 0}
                <li class="divider js-multiple-insert-hide"></li>
            {/if}


            {foreach from=$Grid.Details name=Details item=Detail}
                <li><a class="js-save js-multiple-insert-hide" href="#" data-action="details" data-index="{$smarty.foreach.Details.index}">{$Detail.Caption|string_format:$Captions->GetMessageString('SaveAndOpenDetail')}</a></li>
            {/foreach}
        </ul>
        {/if}
    </div>

    <div class="btn-group">
        <a class="btn btn-default" href="{$Grid.CancelUrl}">{$Captions->GetMessageString('Cancel')}</a>
    </div>

    {if $Grid.FormAction|strpos:'organisation.php' === 0}
      <div class="btn-group">
        <!-- <button id="btn-ws-uid" type="button" class="btn" title="Calls UID webservice and fetches organisation name, location, … Precondition: UID CHE-Number is filled." {*onclick="jQuery('#name_de_edit').val('xxx'); alert('Hi'); return false;"*}>Call UID WS</button> -->
        <a class="btn btn-default" href="#" id="btn-ws-uid" title="Calls UID webservice and fetches organisation name, location, … Precondition: UID CHE-Number is filled." {*onclick="jQuery('#name_de_edit').val('xxx'); alert('Hi'); return false;"*}>Call UID WS</a>
        {* <div id="ws-uid-indicator" class="ws-indicator-small" style="display:none"></div> *}
      </div>
    {/if}
{/capture}

{include file="forms/actions_wrapper.tpl" ActionsContent=$ActionsContent isHorizontal=$isHorizontal top=$top}

<!-- UID-WS messages -->
<div id="info-message" class="alert alert-info" style="display:none">
  <!-- <button class="close" data-dismiss="alert"><i class="icon-remove"></i></button> -->
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>

<div id="error-message" class="alert alert-danger" style="display:none">
  <!-- <button class="close" data-dismiss="alert"><i class="icon-remove"></i></button> -->
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
</div>
