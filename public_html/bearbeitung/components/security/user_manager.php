<?php

interface IUserManager
{
    /**
     * @param string $name
     * @param string $password
     */
    public function addUser($name, $password);

    /**
     * @param string $name
     * @param string $password
     * @param string $email
     * @param string|null $token
     * @param int $status
     */
    public function addUserEx($name, $password, $email, $token = null, $status = UserStatus::OK);

    /**
     * @param int $id
     * @param string $newUsername
     */
    public function renameUser($id, $newUsername);

    /**
     * @param int $id
     * @param string $name
     * @param string $email
     * @param int $status
     */
    public function updateUser($id, $name, $email, $status);

    /**
     * @param int $id
     * @param string $password
     */
    public function changeUserPassword($id, $password);

    /**
     * @param int $id
     */
    public function removeUser($id);
}
