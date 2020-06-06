{capture assign="ContentBlock"}

{if $Authentication.canManageUsers}
<div id="pg-admin-create-user-dialog" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"><span aria-hidden="true">&times;</span></span></button>
                <h4 class="modal-title">{$Captions->GetMessageString('CreateUser')}</h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="newuser-username">{$Captions->GetMessageString('Name')}</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" id="newuser-username" name="name" data-bind="value: newUser.name" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="newuser-password">{$Captions->GetMessageString('Password')}</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="password" id="newuser-password" name="password" data-bind="value: newUser.password" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="newuser-confirmed-password">{$Captions->GetMessageString('ConfirmPassword')}</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="password" id="newuser-confirmed-password" name="confirmedPassword" data-bind="value: newUser.confirmedPassword" />
                        </div>
                    </div>

                    {if $Authentication.EmailBasedFeaturesEnabled}
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="newuser-email">{$Captions->GetMessageString('Email')}</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="text" id="newuser-email" name="email" data-bind="value: newUser.email" />
                        </div>
                    </div>
                    {/if}

                    <div class="form-group">
                        <div class="alert alert-warning" id="newuser-confirmed-password-error">
                            <p>{$Captions->GetMessageString('ConfirmedPasswordError')}</p>
                        </div>
                    </div>

                </form>
            </div>

            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal" id="close-create-user-dialog">{$Captions->GetMessageString('Close')}</a>
                <a href="#" class="btn btn-primary" id="save-create-user-dialog">{$Captions->GetMessageString('CreateUser')}</a>
            </div>
        </div>
    </div>
</div>

<div id="pg-admin-edit-user-dialog" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">
                    {if $Authentication.EmailBasedFeaturesEnabled}
                        {$Captions->GetMessageString('EditUser')}
                    {else}
                        {$Captions->GetMessageString('RenameUser')}
                    {/if}
                </h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal">
                    <fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="user-username">{$Captions->GetMessageString('Name')}</label>
                            <div class="col-sm-9">
                                <input class="form-control" id="user-username" type="text" name="id" data-bind="value: editUser.name" />
                            </div>
                        </div>

                        {if $Authentication.EmailBasedFeaturesEnabled}
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="user-email">{$Captions->GetMessageString('Email')}</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="text" id="user-email" name="email" data-bind="value: editUser.email" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label" for="user-status">{$Captions->GetMessageString('Status')}</label>
                            <div class="col-sm-9">
                                <select class="form-control" id="user-status" name="status" data-bind="value: editUser.status">
                                    <option value="0">{$Captions->GetMessageString('Ok')}</option>
                                    <option value="1">{$Captions->GetMessageString('AccountVerificationRequired')}</option>
                                    <option value="2">{$Captions->GetMessageString('PasswordResetRequested')}</option>
                                </select>
                            </div>
                        </div>
                        {/if}
                    </fieldset>
                </form>
            </div>

            <div class="modal-footer">
                <a href="#" class="btn btn-default" data-dismiss="modal" id="close-edit-user-dialog">{$Captions->GetMessageString('Close')}</a>
                <a href="#" class="btn btn-primary" id="save-edit-user-dialog">{$Captions->GetMessageString('SaveChanges')}</a>
            </div>
        </div>
    </div>
</div>
{/if}

{include file="page_header.tpl" pageTitle=$Captions->GetMessageString('AdministrationPanel')}

{if $Authentication.canManageUsers}
<div class="addition-block">
    <div class="btn-toolbar">
        <div class="btn-group">
            <button class="btn btn-default add-user" data-bind="click: invokeAddUserDialog">
                <i class="icon-user-add"></i>
                {$Captions->GetMessageString('AddUser')}
            </button>
        </div>
    </div>
</div>
{/if}

<table class="table">

    <thead>
        <tr class="header">
            <th>{$Captions->GetMessageString('Actions')}</th>
            <th>{$Captions->GetMessageString('Username')}</th>
        </tr>
    </thead>

{literal}
    <tbody data-bind="template: { name: 'usersRowTemplate', foreach: usersOnCurrentPage }">
    <script type="text/html" id="usersRowTemplate">

        <tr class="pg-row users-row">
            <td>
                <span data-bind="css: { expanded: grantsExpanded() == true }">
                    <button class="btn btn-default" title="Show user grants"
                            data-bind="click: toggleGrantsExpanded, css: { expanded: grantsExpanded() == true }">
                        <i class="icon-permissions"></i>
                        {/literal}<span class="hidden-xs">{$Captions->GetMessageString('Permissions')}{literal}</span>
                    </button>
                </span>

                {/literal}{if $Authentication.canManageUsers}{literal}
                <button class="btn btn-default" title="Rename user"
                        data-bind="click: function() { PhpGenAdmin.adminPanelViewModel.invokeEditUserDialog($data); }, visible: editable">
                    <i class="icon-edit"></i>
                    {/literal}
                        <span class="hidden-xs">
                            {if $Authentication.EmailBasedFeaturesEnabled}
                                {$Captions->GetMessageString('EditUser')}
                            {else}
                                {$Captions->GetMessageString('Rename')}
                            {/if}
                        </span>
                    {literal}
                </button>

                <button class="btn btn-default" title="Change user password"
                        data-bind="click: function() { PhpGenAdmin.adminPanelViewModel.invokeChangeUserPasswordDialog($data); }, visible: editable">
                    <i class="icon-password-change"></i>
                    {/literal}<span class="hidden-xs">{$Captions->GetMessageString('ChangePassword')}</span>{literal}
                </button>

                <button class="btn btn-default" title="Delete user"
                        data-bind="click: function() { PhpGenAdmin.adminPanelViewModel.invokeRemoveUserDialog($data); }, visible: editable">
                    <i class="icon-user-delete"></i>
                    {/literal}<span class="hidden-xs">{$Captions->GetMessageString('Delete')}</span>{literal}
                </button>
                {/literal}{/if}{literal}

            </td>

            <td class="user-name"><span data-bind="text: name"></span></td>
        </tr>

        <tr>
            <td colspan="2" data-bind="visible: grantsExpanded">
                <div class="loading-panel" data-bind="visible: !grantsLoaded()">
                    <div class="loading-panel-content">{/literal}{$Captions->GetMessageString('LoadingDots')}{literal}</div>
                </div>
                <table class="table" data-bind="visible: grantsLoaded()">
                    <thead>
                    <tr class="header">
                        <th>{/literal}{$Captions->GetMessageString('PageName')}{literal}</th>
                        <th>{/literal}{$Captions->GetMessageString('Admin')}{literal}</th>
                        <th>{/literal}{$Captions->GetMessageString('Select')}{literal}</th>
                        <th>{/literal}{$Captions->GetMessageString('Update')}{literal}</th>
                        <th>{/literal}{$Captions->GetMessageString('Insert')}{literal}</th>
                        <th>{/literal}{$Captions->GetMessageString('Delete')}{literal}</th>
                    </tr>
                    </thead>
                    <tbody data-bind="template: { name: 'userGrantsTemplate', foreach: grants }"></tbody>
                </table>
            </td>
        </tr>

    </script>

    <script type="text/html" id="userGrantsTemplate">
        <tr data-bind="css: {warning: isApplication()}">
            <td class="page-caption"><span data-bind="text: caption"></span></td>
            <td class="page-grant"><input type="checkbox" data-bind="checked: adminGrant" /></td>
            <td class="page-grant"><input type="checkbox" data-bind="checked: selectGrant" /></td>
            <td class="page-grant"><input type="checkbox" data-bind="checked: updateGrant" /></td>
            <td class="page-grant"><input type="checkbox" data-bind="checked: insertGrant" /></td>
            <td class="page-grant"><input type="checkbox" data-bind="checked: deleteGrant" /></td>
        </tr>
    </script>
    </tbody>
{/literal}
</table>

<script type="text/javascript">
    {literal}
        window.PhpGenAdmin = {CurrentUsers: {/literal}{$Users}{literal}, EmailBasedFeaturesEnabled: {/literal}{if $Authentication.EmailBasedFeaturesEnabled}true{else}false{/if}{literal}};
    {/literal}
</script>

{/capture}

{* Base template *}
{include file=$LayoutTemplateName}
