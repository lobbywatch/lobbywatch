{include file="page_header.tpl" pageTitle=$Grid.Title pageWithForm=true}

<div class="col-md-12 js-form-container">
    {include file='forms/multi_upload_actions.tpl' top=true}

    <div class="row">
        <div class="js-form-collection col-lg-8">
            {$Form}
        </div>
    </div>

    {include file='forms/multi_upload_actions.tpl' top=false}
</div>


