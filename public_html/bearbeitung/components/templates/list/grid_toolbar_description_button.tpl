{if $Page->getDetailedDescription()}
    <div class="btn-group">
        <button type="button" class="btn btn-default" data-toggle="modal" data-target="#detailedDescriptionModal" title="{$Captions->GetMessageString('PageDescription')}"><i class="icon-question"></i></button>
    </div>

    <div class="modal fade" id="detailedDescriptionModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    {$Page->getDetailedDescription()}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{$Captions->GetMessageString('Close')}</button>
                </div>
            </div>
        </div>
    </div>
{/if}
