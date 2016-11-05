<div class="row">
    {foreach from=$Forms item=Form name=forms}
        <div class="col-md-12">
            {$Form}
            {if not $smarty.foreach.forms.last}<hr>{/if}
        </div>
    {/foreach}
</div>

<div class="btn-toolbar pull-right">

    <button class="btn btn-default js-cancel">
        {$Captions->GetMessageString('Cancel')}
    </button>
    
    <div class="btn-group">
        <button type="submit" class="btn btn-primary js-save js-primary-save">
            {$Captions->GetMessageString('Save')}
        </button>
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><a href="#" class="js-save" data-action="edit">{$Captions->GetMessageString('SaveAndEdit')}</a></li>
        </ul>
    </div>

</div>