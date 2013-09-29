<?php

interface IDataSourceSecurityInfo
{
    public function HasEditGrant(); 

    public function HasViewGrant();

    public function HasDeleteGrant();

    public function HasAddGrant();

    public function AdminGrant();
}

// TODO : create a base class or interface
class AdminDataSourceSecurityInfo implements IDataSourceSecurityInfo
{
    public function __construct()
    { }

    public function HasEditGrant() 
    { 
        return true; 
    }

    public function HasViewGrant() 
    { 
        return true; 
    }

    public function HasDeleteGrant() 
    { 
        return true; 
    }

    public function HasAddGrant() 
    { 
        return true; 
    }

    public function AdminGrant() 
    { 
        return true; 
    }

    public function __toString()
    {
        return 'AdminSecurityInfo';
    }
}

class DataSourceSecurityInfo implements IDataSourceSecurityInfo
{
    private $viewGrant;
    private $editGrant;
    private $addGrant;
    private $deleteGrant;
    
    public function __construct($viewGrant, $editGrant, $addGrant, $deleteGrant)
    {
        $this->viewGrant = $viewGrant;
        $this->editGrant = $editGrant;
        $this->addGrant = $addGrant;
        $this->deleteGrant = $deleteGrant;
    }
    
    public function HasEditGrant() 
    { 
        return $this->editGrant; 
    }  

    public function HasViewGrant() 
    { 
        return $this->viewGrant; 
    }

    public function HasDeleteGrant() 
    { 
        return $this->deleteGrant; 
    }
    
    public function HasAddGrant() 
    { 
        return $this->addGrant; 
    }

    public function AdminGrant() 
    {   
        return false; 
    }

    public function __toString()
    {
        return 'View: ' . $this->HasViewGrant() ? 'true' : 'false' . '; ' .
            'Edit: ' . $this->HasEditGrant() ? 'true' : 'false' . '; ' .
            'Add: ' . $this->HasAddGrant() ? 'true' : 'false' . '; ' .
            'Delete: ' . $this->HasDeleteGrant() ? 'true' : 'false' . '; '.
            'Admin: ' . $this->AdminGrant() ? 'true' : 'false' . '; ';
    }
}

class CompositeSecurityInfo implements IDataSourceSecurityInfo
{
    /**
     * @var IDataSourceSecurityInfo[]
     */
    private $securityInfos;

    public function __construct($securityInfos)
    {
        $this->securityInfos = $securityInfos;
    }
    
    public function HasViewGrant()
    {
        foreach($this->securityInfos as $securityInfo)
            if ($securityInfo->AdminGrant() || $securityInfo->HasViewGrant())
                return true;
        return false;
    }

    public function HasEditGrant()
    {
        foreach($this->securityInfos as $securityInfo)
            if ($securityInfo->AdminGrant() || $securityInfo->HasEditGrant())
                return true;
        return false;
    }

    public function HasDeleteGrant()    
    {
        foreach($this->securityInfos as $securityInfo)
            if ($securityInfo->AdminGrant() || $securityInfo->HasDeleteGrant())
                return true;
        return false;
    }

    public function HasAddGrant()
    {
        foreach($this->securityInfos as $securityInfo)
            if ($securityInfo->AdminGrant() || $securityInfo->HasAddGrant())
                return true;
        return false;
    }

    public function AdminGrant()
    {
        foreach($this->securityInfos as $securityInfo)
            if ($securityInfo->AdminGrant())
                return true;
        return false;
    }

}

class SecurityInfoUtils
{
    public static function Merge($securityInfos)
    {
        return new CompositeSecurityInfo($securityInfos);
    }
}
