<?php

interface IUserManager
{
    /**
     * @return bool
     */
    public function CanAddUser();

    /**
     * @param int $id
     * @param string $userName
     * @param string $password
     */
    public function AddUser($id, $userName, $password);

    /**
     * @return bool
     */
    public function CanChangeUserName();

    /**
     * @param int $id
     * @param string $userName
     */
    public function ChangeUserName($id, $userName);

    /**
     * @return bool
     */
    public function CanChangeUserPassword();

    /**
     * @param int $id
     * @param string $password
     */
    public function ChangeUserPassword($id, $password);

    /**
     * @return bool
     */
    public function CanRemoveUser();

    /**
     * @param int $id
     */
    public function RemoveUser($id);
}