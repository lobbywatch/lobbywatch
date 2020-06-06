<div class="signature-pad">
    <canvas width="{$Editor->getDrawAreaWidth()}" height="{$Editor->getDrawAreaHeight()}"></canvas>
    <input type="hidden" value="{$Editor->GetValue()|escape}"
        data-pen-color="{$Editor->getPenColor()}"
        data-background-color="{$Editor->getBackgroundColor()}"
        data-format-for-saving="{$Editor->getFormatForSaving()}"
        {include file="editors/editor_options.tpl" Editor=$Editor}
    />
    <div class="btn-toolbar">
        <button class="btn btn-default" data-action="clear">{$Captions->GetMessageString('Clear')}</button>
        <button class="btn btn-default" data-action="undo" disabled="disabled">{$Captions->GetMessageString('Undo')}</button>
    </div>
</div>