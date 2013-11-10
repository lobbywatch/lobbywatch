<!-- <Pages> -->

<form class="form-inline">
    <label class="control-label">
        {$PageNavigator->GetCaption()}
    </label>

    <select id="{$PageNavigator->GetName()}">
    {foreach item=PageNavigatorPage from=$PageNavigatorPages}
        {if $PageNavigatorPage->IsCurrent()}
            <option value="" selected="selected">{$PageNavigatorPage->GetPageCaption()}</option>
            {else}
            <option value="{$PageNavigatorPage->GetPageLink()}">{$PageNavigatorPage->GetPageCaption()}</option>
        {/if}
    {/foreach}
    </select>

    <button class="btn" onclick="if ($(this).closest('form').children('#{$PageNavigator->GetName()}').val() != '') window.location.href = $(this).closest('form').children('#{$PageNavigator->GetName()}').val(); return false">GO</button>
</form>
