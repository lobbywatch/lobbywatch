<?php

include_once dirname(__FILE__) . '/' . 'user_grant_manager.php';

class HardCodedUserGrantManager extends UserGrantManager {
    private $dataSourceGrants;

    /** @var IPermissionSet[] */
    private $applicationGrants;

    /** @var string */
    private $defaultUserName;

    /** @var string */
    private $guestUserName;

    /**
     * @param array $dataSourceGrants
     * @param array $applicationGrants
     * @param string $defaultUserName
     * @param string $guestUserName
     */
    public function __construct($dataSourceGrants, $applicationGrants, $defaultUserName = 'defaultUser', $guestUserName = 'guest')
    {
        $this->dataSourceGrants = $dataSourceGrants;
        $this->applicationGrants = $applicationGrants;
        $this->defaultUserName = $defaultUserName;
        $this->guestUserName = $guestUserName;
    }

    private function ApplyDefaultUserGrants(IPermissionSet $userGrants, $dataSourceName) {
        if (isset($this->applicationGrants[$this->defaultUserName]))
            $defaultUserAppGrants = $this->applicationGrants[$this->defaultUserName];
        else
            $defaultUserAppGrants = new PermissionSet(false, false, false, false);

        if (isset($this->dataSourceGrants[$this->defaultUserName])) {
            if (isset($this->dataSourceGrants[$this->defaultUserName][$dataSourceName]))
                $defaultUserDataSourceGrants = $this->dataSourceGrants[$this->defaultUserName][$dataSourceName];
            else
                $defaultUserDataSourceGrants = new PermissionSet(false, false, false, false);
        } else
            $defaultUserDataSourceGrants = new PermissionSet(false, false, false, false);

        return PermissionSetUtils::Merge(array($defaultUserAppGrants, $defaultUserDataSourceGrants, $userGrants));
    }

    private function ApplyApplicationGrants(IPermissionSet $userGrants, $userName) {
        if (isset($this->applicationGrants[$userName]))
            $userAppGrants = $this->applicationGrants[$userName];
        else
            $userAppGrants = new PermissionSet(false, false, false, false);
        return PermissionSetUtils::Merge(array($userAppGrants, $userGrants));
    }

    private function IsGuestUserName($userName) {
        return $userName == $this->guestUserName;
    }

    public function HasAdminGrant($userName) {
        return isset($this->applicationGrants[$userName]) &&
        $this->applicationGrants[$userName]->HasAdminGrant();
    }

    public function HasAdminPanel($userName) {
        if ($this->HasAdminGrant($userName)) {
            return true;
        }

        foreach ($this->dataSourceGrants as $name => $grants) {
            if (StringUtils::SameText($name, $userName)) {
                foreach ($grants as $grant) {
                    if ($grant->HasAdminGrant()) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function getAdminDatasources($userName)
    {
        $result = array();

        foreach ($this->dataSourceGrants as $name => $grants) {
            if (StringUtils::SameText($name, $userName)) {
                foreach ($grants as $pageName => $grant) {
                    if ($grant->HasAdminGrant()) {
                        $result[] = $pageName;
                    }
                }
            }
        }

        return $result;
    }

    private function FindDataSourceGrants($userName) {
        foreach ($this->dataSourceGrants as $name => $grants) {
            if (StringUtils::SameText($name, $userName)) {
                return $grants;
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function GetPermissionSet($userName, $dataSourceName) {
        $result = new PermissionSet(false, false, false, false);
        $grants = $this->FindDataSourceGrants($userName);

        if (isset($grants)) {
            if (isset($grants[$dataSourceName])) {
                $result = $grants[$dataSourceName];
            }
        }

        if (!$this->IsGuestUserName($userName)) {
            $result = $this->ApplyDefaultUserGrants($result, $dataSourceName);
        }

        $result = $this->ApplyApplicationGrants($result, $userName);

        return $result;
    }
}
