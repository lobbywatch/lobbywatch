<?php

include_once dirname(__FILE__) . '/' . '../user_identity.php';
include_once dirname(__FILE__) . '/' . 'user_identity_storage.php';
include_once dirname(__FILE__) . '/' . '../../utils/array_wrapper.php';


class UserIdentityArrayStorage implements UserIdentityStorage {
    const IDENTITY_KEY = 'current_user';

    /** @var ArrayWrapper */
    private $storage;

    public function __construct()
    {
        $this->storage = new ArrayWrapper(array());
    }

    public function SaveUserIdentity(UserIdentity $identity) {
        $this->storage->setValue(self::IDENTITY_KEY, $identity);
    }

    public function ClearUserIdentity() {
        $this->storage->unsetValue(self::IDENTITY_KEY);
    }

    /**
     * @return UserIdentity|null
     */
    public function getUserIdentity() {
        return $this->storage->getValue(self::IDENTITY_KEY);
    }
}
