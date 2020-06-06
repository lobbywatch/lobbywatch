<div id="pgui-view-grid">
    {include file="page_header.tpl" pageTitle=$Grid.Title}

    <div class="{if $Grid.FormLayout->isHorizontal()}form-horizontal{/if}">

        {include file="forms/actions_view.tpl" top=true isHorizontal=$Grid.FormLayout->isHorizontal()}

        <div class="row">
            <div class="col-md-12 js-message-container"></div>
            <div class="clearfix"></div>

            <div class="{if $Grid.FormLayout->isHorizontal()}form-horizontal col-lg-8{else}col-md-8 col-md-offset-2{/if}">
                {include file='forms/form_fields.tpl' isViewForm=true}
            </div>
        </div>

        {include file="forms/actions_view.tpl" top=false isHorizontal=$Grid.FormLayout->isHorizontal()}
    </div>
</div>
