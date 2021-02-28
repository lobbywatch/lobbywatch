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
                    <li><a id="self-change-password" href="#" title="{$Captions->GetMessageString('ChangePassword')}">{$Captions->GetMessageString('ChangePassword')}</a></li>
                {/if}
                <li><a href="login.php?operation=logout">{$Captions->GetMessageString('Logout')}</a></li>
            </ul>
        </li>
    {else}
        <li><a href="login.php{if $Page and $Page->getLink()}?redirect={$Page->getLink()|@urlencode}{/if}">{$Captions->GetMessageString('Login')}</a></li>
    {/if}
</ul>
