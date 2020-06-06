{foreach item=Group from=$FormSheet->getGroups()}
    {if $Group->getVisible() && count($Group->getRows()) > 0}
        {assign var="customAttributes" value=$Group->getCustomAttributes()}
        {assign var="inlineStyles" value=$Group->getInlineStyles()}
        <fieldset class="col-md-{$Group->getWidth()}{if $isViewForm} form-static{/if}{if $Group->isHorizontal()} form-horizontal{/if}"{if $customAttributes} {$customAttributes}{/if}{if $inlineStyles} {style_block}{$inlineStyles}{/style_block}{/if}>
            {if $Group->getName()}
                <legend>
                    {$Group->getName()}
                </legend>
            {/if}
            {foreach item=Row from=$Group->getRows()}
                <div class="row">
                    {foreach item=Col from=$Row->getCols()}
                        {include file='forms/form_field.tpl' isHorizontalMode=$Group->isHorizontal()}
                    {/foreach}
                </div>
            {/foreach}
        </fieldset>
    {/if}
{/foreach}
