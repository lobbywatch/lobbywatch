<?php

interface IPermissionSet
{
    public function HasEditGrant();
    public function HasViewGrant();
    public function HasDeleteGrant();
    public function HasAddGrant();
    public function HasAdminGrant();
}

class AdminPermissionSet implements IPermissionSet
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

    public function HasAdminGrant()
    {
        return true;
    }

    public function __toString()
    {
        return 'AdminSecurityInfo';
    }
}

class PermissionSet implements IPermissionSet
{
    private $viewGrant;
    private $editGrant;
    private $addGrant;
    private $deleteGrant;
    private $adminGrant;

    public function __construct($viewGrant, $editGrant, $addGrant, $deleteGrant, $adminGrant = false)
    {
        $this->viewGrant = $viewGrant;
        $this->editGrant = $editGrant;
        $this->addGrant = $addGrant;
        $this->deleteGrant = $deleteGrant;
        $this->adminGrant = $adminGrant;
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

    public function HasAdminGrant()
    {
        return $this->adminGrant;
    }

    public function setViewGrant($viewGrant)
    {
        $this->viewGrant = $viewGrant;
    }

    public function setEditGrant($editGrant)
    {
        $this->editGrant = $editGrant;
    }

    public function setDeleteGrant($deleteGrant)
    {
        $this->deleteGrant = $deleteGrant;
    }

    public function setAddGrant($addGrant)
    {
        $this->addGrant = $addGrant;
    }

    public function setAdminGrant($adminGrant)
    {
        $this->adminGrant = $adminGrant;
    }

    public function setGrants($viewGrant, $addGrant, $editGrant, $deleteGrant)
    {
        $this->viewGrant = $viewGrant;
        $this->addGrant = $addGrant;
        $this->editGrant = $editGrant;
        $this->deleteGrant = $deleteGrant;
    }

    public function __toString()
    {
        return 'View: ' . ($this->HasViewGrant() ? 'true' : 'false') . '; ' .
            'Edit: ' . ($this->HasEditGrant() ? 'true' : 'false') . '; ' .
            'Add: ' . ($this->HasAddGrant() ? 'true' : 'false') . '; ' .
            'Delete: ' . ($this->HasDeleteGrant() ? 'true' : 'false') . '; '.
            'Admin: ' . ($this->HasAdminGrant() ? 'true' : 'false') . '; ';
    }
}

class CompositePermissionSet implements IPermissionSet
{
    /**
     * @var IPermissionSet[]
     */
    private $securityInfos;

    public function __construct(array $securityInfos)
    {
        $this->securityInfos = $securityInfos;
    }

    public function HasViewGrant()
    {
        foreach($this->securityInfos as $securityInfo) {
            if ($securityInfo->HasAdminGrant() || $securityInfo->HasViewGrant())
                return true;
        }
        return false;
    }

    public function HasEditGrant()
    {
        foreach($this->securityInfos as $securityInfo)
            if ($securityInfo->HasAdminGrant() || $securityInfo->HasEditGrant())
                return true;
        return false;
    }

    public function HasDeleteGrant()
    {
        foreach($this->securityInfos as $securityInfo)
            if ($securityInfo->HasAdminGrant() || $securityInfo->HasDeleteGrant())
                return true;
        return false;
    }

    public function HasAddGrant()
    {
        foreach($this->securityInfos as $securityInfo)
            if ($securityInfo->HasAdminGrant() || $securityInfo->HasAddGrant())
                return true;
        return false;
    }

    public function HasAdminGrant()
    {
        foreach($this->securityInfos as $securityInfo)
            if ($securityInfo->HasAdminGrant())
                return true;
        return false;
    }

}

class PermissionSetUtils
{
    public static function Merge($securityInfos)
    {
        return new CompositePermissionSet($securityInfos);
    }
}
