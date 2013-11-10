<?php

include_once dirname(__FILE__) . '/' . 'datasource_security_info.php';

abstract class UserGrantsManager
{
    /**
     * @param $userName
     * @param $dataSourceName
     * @return IDataSourceSecurityInfo
     */
    public abstract function GetSecurityInfo($userName, $dataSourceName);

    /**
     * @abstract
     * @param string $userName
     * @return boolean
     */
    public abstract function HasAdminGrant($userName);
}

class CompositeGrantsManager extends UserGrantsManager
{
    /** @var UserGrantsManager[] */
    private $grantManagers;

    public function __construct()
    {
        $this->grantManagers = array();
    }

    /**
     * @param UserGrantsManager $grantsManager
     * @return void
     */
    public function AddGrantsManager($grantsManager)
    {
        $this->grantManagers[] = $grantsManager;
    }

    /**
     * @param $userName
     * @param $dataSourceName
     * @return IDataSourceSecurityInfo
     */
    public function GetSecurityInfo($userName, $dataSourceName)
    {
        $securityInfos = array();
        foreach($this->grantManagers as $grantsManager)
            array_push($securityInfos, $grantsManager->GetSecurityInfo($userName, $dataSourceName));
        return new CompositeSecurityInfo($securityInfos);
    }

    /**
     * @param string $userName
     * @return boolean
     */
    public function HasAdminGrant($userName)
    {
        foreach($this->grantManagers as $grantsManager)
        {
            if ($grantsManager->HasAdminGrant($userName))
                return true;
        }
        return false;
    }
}

class HardCodedUserGrantsManager extends UserGrantsManager
{
    private $dataSourceGrants;

    /** @var IDataSourceSecurityInfo[] */
    private $applicationGrants;
    private $defaultUserName;

    public function __construct(
        array $dataSourceGrants, 
        array $applicationGrants, 
        $defaultUserName = 'defaultUser',
        $guestUserName = 'guest')
    {
        $this->dataSourceGrants = $dataSourceGrants;
        $this->applicationGrants = $applicationGrants;
        $this->defaultUserName = $defaultUserName;
        $this->guestUserName = $guestUserName;
    }

    private function ApplyDefaultUserGrants(IDataSourceSecurityInfo $userGrants, $dataSourceName)
    {
        if (isset($this->applicationGrants[$this->defaultUserName]))
            $defaultUserAppGrants = $this->applicationGrants[$this->defaultUserName];
        else    
            $defaultUserAppGrants = new DataSourceSecurityInfo(false, false, false, false);

        if (isset($this->dataSourceGrants[$this->defaultUserName]))
        {
            if (isset($this->dataSourceGrants[$this->defaultUserName][$dataSourceName]))
                $defaultUserDataSourceGrants = $this->dataSourceGrants[$this->defaultUserName][$dataSourceName];
            else
                $defaultUserDataSourceGrants = new DataSourceSecurityInfo(false, false, false, false);
        }
        else    
            $defaultUserDataSourceGrants = new DataSourceSecurityInfo(false, false, false, false);

        return SecurityInfoUtils::Merge(array($defaultUserAppGrants, $defaultUserDataSourceGrants, $userGrants));
    }

    private function ApplyApplicationGrants(IDataSourceSecurityInfo $userGrants, $userName)
    {
        if (isset($this->applicationGrants[$userName]))
            $userAppGrants = $this->applicationGrants[$userName];
        else    
            $userAppGrants = new DataSourceSecurityInfo(false, false, false, false);
        return SecurityInfoUtils::Merge(array($userAppGrants, $userGrants));
    }

    private function IsGuestUserName($userName)
    {
        return $userName == $this->guestUserName;
    }

    public function HasAdminGrant($userName)
    {
        return isset($this->applicationGrants[$userName]) &&
               $this->applicationGrants[$userName]->AdminGrant();
    }

    private function FindDataSourceGrants($userName)
    {
        foreach($this->dataSourceGrants as $name => $grants)
            if (StringUtils::SameText($name, $userName))
                return $this->dataSourceGrants[$name];
        return null;
    }

    public function GetSecurityInfo($userName, $dataSourceName)
    {
        $result = new DataSourceSecurityInfo(false, false, false, false);
        $grants = $this->FindDataSourceGrants($userName);
        if (isset($grants))
        {   
            if (isset($grants[$dataSourceName]))
            {
                $result = $grants[$dataSourceName];
            }
        }
        if (!$this->IsGuestUserName($userName))            
            $result = $this->ApplyDefaultUserGrants($result, $dataSourceName);
        $result = $this->ApplyApplicationGrants($result, $userName);
        return $result;
    }
}
