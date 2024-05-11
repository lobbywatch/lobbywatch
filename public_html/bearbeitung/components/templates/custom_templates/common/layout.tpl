<!DOCTYPE html>
<html{if $common->getDirection()} dir="{$common->getDirection()}"{/if}>
<head>
    <meta name="Generator" content="PHP Generator for MySQL (https://sqlmaestro.com)" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1">
    <meta name="robots" content="noindex, nofollow">
    {if $common->getContentEncoding()}
        <meta charset="{$common->getContentEncoding()}">
    {/if}
    {$common->getCustomHead()}
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    {if $common}
        <title>{$common->getTitle()}</title>
    {else}
        <title>Error</title>
    {/if}

    <link rel="stylesheet" type="text/css" href="{$StyleFile|default:'components/assets/css/main.css'}{if empty($StyleFile)}?h={$hash_css_main}{/if}" />
    <link rel="stylesheet" type="text/css" href="components/assets/css/custom/custom.css?h={$hash_css_custom}" />
    {if !GetOfflineMode() && isset($ExternalServicesLoadingBlock)}
        {$ExternalServicesLoadingBlock}
    {/if}

    {if $common}
    <script>{literal}
        window.beforePageLoad = function () {
            {/literal}{$common->getClientSideScript('OnBeforeLoadEvent')}{literal}
        }
        window.afterPageLoad = function () {
            {/literal}{$common->getClientSideScript('OnAfterLoadEvent')}{literal}
        }
    {/literal}</script>
    {/if}

    {include file='common/recaptcha.tpl'}

    <script type="text/javascript" src="components/js/require-config.js"></script>
    {if UseMinifiedJS()}
        <script type="text/javascript" src="components/js/libs/require.js"></script>
        <script type="text/javascript" src="components/js/main-bundle-custom.js?h={$hash_js_main_bundle}"></script>
    {else}
        <script type="text/javascript" data-main="main" src="components/js/libs/require.js"></script>
    {/if}
</head>

{if isset($Page)}
    {assign var="PageListObj" value=$Page->GetReadyPageList()}
    {if $PageListObj and $Page->GetShowPageList()}
        {if $PageListObj->isTypeSidebar()}
            {capture assign="SideBar"}
                {if isset($Sidebar)}{$Sidebar}{/if}
                {$PageList}
            {/capture}
        {/if}

        {if $PageListObj->isTypeMenu()}
            {capture assign="Menu"}
                {if isset($Menu)}{$Menu}{/if}
                {$PageList}
            {/capture}
        {/if}
    {/if}
{/if}

{if !isset($HideSideBarByDefault)}
    {assign var="HideSideBarByDefault" value=false}
{/if}

<body{if isset($Page)} id="pgpage-{$Page->GetPageId()}"{/if}{if isset($SideBar) and not $HideSideBarByDefault} class="sidebar-desktop-active"{/if} data-page-entry="{$common->getEntryPoint()}" data-inactivity-timeout="{$common->getInactivityTimeout()}"{if isset($InactivityTimeoutExpired) && $InactivityTimeoutExpired} data-inactivity-timeout-expired="true"{/if}>
<nav id="navbar" class="navbar navbar-default navbar-fixed-top">

    {if isset($SideBar)}
        <div class="toggle-sidebar pull-left" title="{$Captions->GetMessageString('SidebarToggle')}">
            <button class="icon-toggle-sidebar"></button>
        </div>
    {/if}

    <div class="container-fluid">
        <div class="navbar-header">
            {if $common}
                {$common->getHeader()}
            {/if}
            {if isset($Menu) or isset($NavbarContent) or (isset($Authentication) && $Authentication.Enabled)}
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navnav" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            {/if}
        </div>

        <div class="navbar-collapse collapse" id="navnav">
            {if isset($NavbarContent)}{$NavbarContent}{/if}

            {if isset($Authentication) && $Authentication.Enabled}
                {include file='common/user_menu.tpl'}
            {/if}

            {if isset($Menu)}
                {$Menu}
            {/if}
        </div>
    </div>
</nav>

<div class="container-fluid">

    <div class="row{if isset($SideBar)} sidebar-owner{/if}">

        {if isset($SideBar)}

            <div class="sidebar">
                <div class="content">
                    {$SideBar}
                </div>
            </div>
            <div class="sidebar-backdrop"></div>
        {/if}

        <div class="{if isset($ContentBlockClass)}{$ContentBlockClass}{else}col-md-12{/if}">
            {if isset($SideBar)}<div class="sidebar-outer">{/if}
                <div class="container-padding">
                    {$ContentBlock}
		    {if isset($Variables)}{$Variables}{/if}

                    {if $common->getFooter()}
                        <hr>
                        <footer>
                            {$common->getFooter()}
                        </footer>
                    {/if}

                    {php}
                        global $Page;

                        if (DebugUtils::GetDebugLevel() > 0 && $Page instanceOf Page) {
                            echo sprintf('<p><pre>%s queries</pre></p>', count($Page->getConnection()->getQueryLog()));
                            echo sprintf('<p><pre>%s</pre></p>', implode("\n", $Page->getConnection()->getQueryLog()));
                        }

                    {/php}
                </div>
            {if isset($SideBar)}</div>{/if}
        </div>

    </div>
</div>
{include file='common/change_password_dialog.tpl'}
</body>
</html>
