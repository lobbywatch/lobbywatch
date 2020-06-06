<div class="modal-dialog {$modalSizeClass}">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">{$Grid.Title}</h4>
        </div>
        <div class="modal-body">

            <div class="{if $Grid.FormLayout->isHorizontal()} form-horizontal{/if}">
                <div class="row">
                    <div class="col-md-12 js-message-container"></div>

                    <div class="col-md-12">
                        {include file='forms/form_fields.tpl' isViewForm=true}
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">{$Captions->GetMessageString('Close')}</button>
        </div>
    </div>
</div>