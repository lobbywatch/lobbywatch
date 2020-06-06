<!DOCTYPE html>
<html{if $common->getDirection()} dir="{$common->getDirection()}"{/if}>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1">
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

    <link rel="stylesheet" type="text/css" href="{$StyleFile|default:'components/assets/css/main.css'}" />
    {if !GetOfflineMode()}
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
        <script type="text/javascript" src="components/js/main-bundle.js"></script>
    {else}
        <script type="text/javascript" data-main="main" src="components/js/libs/require.js"></script>
    {/if}
</head>

{if $Page}
    {assign var="PageListObj" value=$Page->GetReadyPageList()}
    {if $PageListObj and $Page->GetShowPageList()}
        {if $PageListObj->isTypeSidebar()}
            {capture assign="SideBar"}
                {$Sidebar}
                {$PageList}
            {/capture}
        {/if}

        {if $PageListObj->isTypeMenu()}
            {capture assign="Menu"}
                {$Menu}
                {$PageList}
            {/capture}
        {/if}
    {/if}
{/if}

<body{if $Page} id="pgpage-{$Page->GetPageId()}"{/if}{if $SideBar and not $HideSideBarByDefault} class="sidebar-desktop-active"{/if} data-page-entry="{$common->getEntryPoint()}" data-inactivity-timeout="{$common->getInactivityTimeout()}"{if $InactivityTimeoutExpired} data-inactivity-timeout-expired="true"{/if}>
<nav id="navbar" class="navbar navbar-default navbar-fixed-top">

    {if $SideBar}
        <div class="toggle-sidebar pull-left" title="{$Captions->GetMessageString('SidebarToggle')}">
            <button class="icon-toggle-sidebar"></button>
        </div>
    {/if}

    <div class="container-fluid">
        <div class="navbar-header">
            {if $common}
                {$common->getHeader()}
            {/if}
            {if $Menu or $NavbarContent or $Authentication.Enabled}
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navnav" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            {/if}
        </div>

        <div class="navbar-collapse collapse" id="navnav">
            {if $NavbarContent}{$NavbarContent}{/if}

            {if $Authentication.Enabled}
                <ul id="nav-menu" class="nav navbar-nav navbar-right">
                    {if $Authentication.LoggedIn}
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="icon-user"></i>
                                {if $Authentication.CurrentUser.Name == 'guest'}
                                    {$Captions->GetMessageString('Guest')}
                                {else}
                                    {$Authentication.CurrentUser.Name}
                                {/if}
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                {if $Authentication.isAdminPanelVisible}
                                    <li><a href="phpgen_admin.php" title="{$Captions->GetMessageString('AdminPage')}">{$Captions->GetMessageString('AdminPage')}</a></li>
                                    <li role="separator" class="divider"></li>
                                {/if}
                                {if $Authentication.CanChangeOwnPassword}
                                    <li><a id="self-change-password" href="#" title="{$Captions->GetMessageString('ChangePassword')}">
                                            {$Captions->GetMessageString('ChangePassword')}
                                        </a>
                                    </li>
                                {/if}
                                <li><a href="login.php?operation=logout">{$Captions->GetMessageString('Logout')}</a></li>
                            </ul>
                        </li>
                    {else}
                        <li><a href="login.php{if $Page and $Page->getLink()}?redirect={$Page->getLink()|@urlencode}{/if}">{$Captions->GetMessageString('Login')}</a></li>
                    {/if}
                </ul>
            {/if}

            {if $Menu}
                {$Menu}
            {/if}
        </div>
    </div>
</nav>


{if !isset($HideSideBarByDefault)}
    {assign var="HideSideBarByDefault" value=false}
{/if}


<div class="container-fluid">

    <div class="row{if $SideBar} sidebar-owner{/if}">

        {if $SideBar}

            <div class="sidebar">
                <div class="content">
                    {$SideBar}
                </div>
            </div>
            <div class="sidebar-backdrop"></div>
        {/if}

        <div class="{if isset($ContentBlockClass)}{$ContentBlockClass}{else}col-md-12{/if}">
            {if $SideBar}<div class="sidebar-outer">{/if}
                <div class="container-padding">
                    {$ContentBlock}
                    {$Variables}

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
            {if $SideBar}</div>{/if}
        </div>

    </div>
</div>
{include file='common/change_password_dialog.tpl'}
</body>
</html>