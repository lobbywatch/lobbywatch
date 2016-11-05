{if $pageTitle or $navigation}
    <div class="page-header{if $pageWithForm} form-header{/if}">
        {if $navigation}{$navigation}{/if}
        {if $pageTitle}<h1>{$pageTitle}</h1>{/if}
    </div>
{/if}