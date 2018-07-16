<?php

include_once dirname(__FILE__) . '/user_grant_manager.php';

class ServerSideUserGrantManager extends NullUserGrantManager
{
    /** @var boolean  */
    private $allowGuestAccess;

    /**
     * @param boolean $allowGuestAccess
     */
    public function __construct($allowGuestAccess)
    {
        $this->allowGuestAccess = $allowGuestAccess;
    }

    /** @inheritdoc */
    public function GetPermissionSet($userName, $dataSourceName) {
        if (($userName == 'guest') and (!$this->allowGuestAccess))
            return new PermissionSet(false, false, false, false);
        else
            return new PermissionSet(true, true, true, true);
    }
}
