{if isset($pageTitle) or isset($navigation)}
    <div class="page-header{if isset($pageWithForm) && $pageWithForm} form-header{/if}">
        {if isset($navigation)}{$navigation}{/if}
        {if isset($pageTitle)}<h1>{$pageTitle}</h1>{/if}
    </div>
{/if}
