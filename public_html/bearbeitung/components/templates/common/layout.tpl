<!DOCTYPE html>
<html dir="{$App.Direction}">
<head>
    {if $App.ContentEncoding}
        <meta charset="UTF-8">
    {/if}
    {$App.CustomHtmlHeadSection}
    <meta http-equiv="X-UA-Compatible" content="IE=8, IE=9, IE=10">
    {if $App}
        <title>{$App.PageCaption}</title>
    {else}
        <title>Error</title>
    {/if}

    <link rel="stylesheet" type="text/css" href="components/css/main.css" />
    <link rel="stylesheet" type="text/css" href="components/css/user.css" />

    <script src="components/js/jquery/jquery.min.js"></script>
    <script src="components/js/libs/amplify.store.js"></script>
    <script src="components/js/bootstrap/bootstrap.js"></script>

    <script type="text/javascript" src="components/js/require-config.js"></script>
    {if $JavaScriptMain}
        <script type="text/javascript" data-main="{$JavaScriptMain}" src="components/js/require.js"></script>
    {else}
        <script type="text/javascript" src="components/js/require.js"></script>
    {/if}

    <script>{literal}
        require(['pgui.layout'], function(layout_manager){
            layout_manager.fixLayout();
        });
    {/literal}</script>

    {if $App}
    <script>{$App.ValidationScripts}</script>
    {/if}
    <script>{$Scripts}</script>
</head>
<body>

<div class="navbar" id="navbar">
    <div class="navbar-inner">
        <div class="container">
            {if $App}
            <div class="pull-left">{$App.Header}</div>
            {/if}

        {if $Authentication.Enabled}
            <ul id="login-panel" class="nav pull-right">
                <li class="active">
                    <a href="#" onclick="return false;" style="cursor: default;">
                        <i class="pg-icon-user"></i>
                        {$Authentication.CurrentUser.Name}</a>
                </li>
                {if $Authentication.LoggedIn}
                    {if $Authentication.CanChangeOwnPassword}
                    <li><a id="self-change-password" href="#" title="{$Captions->GetMessageString('ChangePassword')}">
                            <i class="pg-icon-password-change"></i>
                        </a>
                    </li>
                    {/if}
                    <li><a href="login.php?operation=logout">{$Captions->GetMessageString('Logout')}</a></li>
                    {else}
                    <li><a href="login.php">{$Captions->GetMessageString('Login')}</a></li>
                {/if}
            </ul>
        {/if}
        </div>
    </div>
</div>

{if !isset($HideSideBarByDefault)}
    {assign var="HideSideBarByDefault" value=false}
{/if}


<div class="container-fluid">
    <div class="row-fluid">
        {if $SideBar}
        <div class="span3 expanded" id="side-bar">

            <div class="sidebar-nav-fixed">
                <a href="#" class="close" style="margin: 4px 4px 0 0"><i class="icon-chevron-left"></i></a>
                <div class="content">
                    {$SideBar}
                </div>
            </div>

            <script>{literal}
            $('.sidebar-nav-fixed').css('top',
                Math.max(0, $('#navbar').outerHeight() - $(window).scrollTop())
            );
            $('#navbar img').load(function() {
                $('.sidebar-nav-fixed').css('top',
                    Math.max(0, $('#navbar').outerHeight() - $(window).scrollTop())
                );
            });
            $(window).scroll(function() {
                $('.sidebar-nav-fixed').css('top',
                        Math.max(0, $('#navbar').outerHeight() - $(window).scrollTop())
                );
            });
            //$('#content').css('top', $('.navbar-fixed-top').height() + 10);
            //$('#side-bar').css('top', $('.navbar-fixed-top').height() - 10);
            {/literal}</script>

        </div>
        {/if}
        <div class="{if $SideBar}span9{else}span12{/if}" id="content-block">
            {if $SideBar}
            <script>{literal}
            var sideBarContainer = $('#side-bar');
            var sidebar = $('#side-bar .sidebar-nav-fixed');
            var toggleButton = sidebar.find('a.close');
            var toggleButtonIcon = toggleButton.children('i');

            function hideSideBar() {
                sideBarContainer.removeClass('expanded');
                sidebar.children('.content').hide();
                sideBarContainer.width(20);
                toggleButtonIcon.removeClass('icon-chevron-left');
                toggleButtonIcon.addClass('icon-chevron-right');
                $('#content-block').css('left', 0);
                $('#content-block').addClass('span10');
                $('#content-block').removeClass('span9');
            }

            function showSideBar() {
                sideBarContainer.addClass('expanded');
                sidebar.children('.content').show();
                sideBarContainer.width(240);
                toggleButtonIcon.addClass('icon-chevron-left');
                toggleButtonIcon.removeClass('icon-chevron-right');
                $('#content-block').css('left', 240);
                $('#content-block').removeClass('span10');
                $('#content-block').addClass('span9');
            }

            {/literal}
            {if $HideSideBarByDefault}
                hideSideBar();
            {else}
                {literal}
                if (amplify.store('side-bar-collapsed')) {
                    hideSideBar();
                }
                {/literal}
            {/if}
            {literal}


            toggleButton.click(function(e) {
                e.preventDefault();
                if (sideBarContainer.hasClass('expanded')) {
                    hideSideBar();
                    amplify.store('side-bar-collapsed', true);
                }
                else {
                    showSideBar();
                    amplify.store('side-bar-collapsed', false);
                }
            });
            {/literal}</script>
            {/if}
            {$ContentBlock}
            {$Variables}
            <hr>
            <footer><p>{$Footer}</p></footer>
        </div>


    </div>
</div>

{include file='common/change_password_dialog.tpl'}
<script type="text/javascript" src="components/js/pg.user_management_api.js"></script>
<script type="text/javascript" src="components/js/pgui.change_password_dialog.js"></script>
<script type="text/javascript" src="components/js/pgui.password_dialog_utils.js"></script>
<script type="text/javascript" src="components/js/pgui.self_change_password.js"></script>

</body>
</html>