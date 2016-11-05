{include file="page_header.tpl" pageTitle=$Grid.Title pageWithForm=true}

<div class="col-md-12 js-form-container" data-form-url="{$Grid.FormAction}&flash=true">
    {include file='forms/actions_edit.tpl' top=true isHorizontal=$Grid.FormLayout->isHorizontal()}

    <div class="row">
        <div class="js-form-collection {if $Grid.FormLayout->isHorizontal()}col-lg-8{else}col-md-8 col-md-offset-2{/if}">
            {foreach from=$Forms item=Form name=forms}
                {$Form}
                {if not $smarty.foreach.forms.last}<hr>{/if}
            {/foreach}
        </div>
    </div>

    {if $Grid.AllowAddMultipleRecords}
        <div class="row" style="margin-top: 20px">
            <div class="{if $Grid.FormLayout->isHorizontal()}col-lg-8{else}col-md-8 col-md-offset-2{/if}">
                <a href="#" class="js-form-add{if $Grid.FormLayout->isHorizontal()} col-md-offset-3{/if}"><span class="icon-plus"></span> {$Captions->GetMessageString('FormAdd')}</a>
            </div>
        </div>
    {/if}

    {include file='forms/actions_edit.tpl' top=false isHorizontal=$Grid.FormLayout->isHorizontal()}
</div>


