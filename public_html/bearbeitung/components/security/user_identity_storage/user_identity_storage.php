<?php

interface UserIdentityStorage {

    /**
     * @param UserIdentity $identity
     */
    public function SaveUserIdentity(UserIdentity $identity);

    public function ClearUserIdentity();

    /**
     * @return UserIdentity|null
     */
    public function getUserIdentity();
}
