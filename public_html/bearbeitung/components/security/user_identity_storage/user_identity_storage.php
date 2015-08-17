<?php

interface UserIdentityStorage {

    /**
     * @param UserIdentity $identity
     */
    public function SaveUserIdentity(UserIdentity $identity);

    public function ClearUserIdentity();

    /**
     * @param string $newPassword
     */
    public function UpdatePassword($newPassword);

    /**
     * @return UserIdentity|null
     */
    public function getUserIdentity();
}
