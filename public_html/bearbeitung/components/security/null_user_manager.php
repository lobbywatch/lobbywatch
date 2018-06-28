<?php

include_once dirname(__FILE__) . '/' . 'user_manager.php';
include_once dirname(__FILE__) . '/' . '../utils/not_implemented_exception.php';

class NullUserManager implements IUserManager
{
    /** @inheritdoc */
    public function addUser($name, $password) {
        throw new NotImplementedException();
    }

    /** @inheritdoc */
    public function addUserEx($name, $password, $email, $token = null, $status = UserStatus::OK) {
        throw new NotImplementedException();
    }

    /** @inheritdoc */
    public function renameUser($user_id, $newUsername) {
        throw new NotImplementedException();
    }

    /** @inheritdoc */
    public function updateUser($id, $name, $email, $status) {
        throw new NotImplementedException();
    }

    /** @inheritdoc */
    public function changeUserPassword($id, $password) {
        throw new NotImplementedException();
    }

    /** @inheritdoc */
    public function removeUser($id) {
        throw new NotImplementedException();
    }
}
