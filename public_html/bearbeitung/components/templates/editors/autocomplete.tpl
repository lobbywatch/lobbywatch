{capture assign='AdditionalProperties'}

    data-minimum-input-length="{$Editor->getMinimumInputLength()}"
    data-url="{$Editor->getDataUrl()}"

{/capture}

{include file="editors/text.tpl" AdditionalProperties=$AdditionalProperties}
