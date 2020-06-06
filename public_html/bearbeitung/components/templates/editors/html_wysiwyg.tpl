<textarea
    {include file="editors/editor_options.tpl" Editor=$Editor}
    class="html_wysiwyg"
    >{$Editor->GetValue()}
</textarea>
<div class="html-templates" style="display: none;">
    {foreach item=HTMLTemplate from=$Editor->getTemplates()}
        <div data-template-name="{$HTMLTemplate->name}">{$HTMLTemplate->html}</div>
    {/foreach}
</div>