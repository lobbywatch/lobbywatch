<?php

include_once dirname(__FILE__) . '/' . 'user_manager.php';
include_once dirname(__FILE__) . '/' . '../utils/not_implemented_exception.php';

class NullUserManager implements IUserManager
{
    /**
     * {@inheritdoc}
     */
    public function CanAddUser()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function AddUser($id, $userName, $password)
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function CanChangeUserName()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function ChangeUserName($user_id, $userName)
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function CanChangeUserPassword()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function ChangeUserPassword($user_id, $password)
    {
        throw new NotImplementedException();
    }

    /**
     * {@inheritdoc}
     */
    public function CanRemoveUser()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function RemoveUser($userId)
    {
        throw new NotImplementedException();
    }
}
