<?php

include_once dirname(__FILE__) . '/user_grant_manager.php';

class UserDefinedUserGrantManager extends NullUserGrantManager
{
    /** @inheritdoc */
    public function GetPermissionSet($userName, $dataSourceName) {
        if ($userName == 'guest')
            $result = new PermissionSet(false, false, false, false);
        else
            $result = new PermissionSet(true, true, true, true);

        return $result;
    }
}
