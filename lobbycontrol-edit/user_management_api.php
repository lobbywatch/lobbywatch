<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

include_once dirname(__FILE__) . '/' . 'authorization.php';
include_once dirname(__FILE__) . '/' . 'components/security/user_management_request_handler.php';

SetUpUserAuthorization();
UserManagementRequestHandler::HandleRequest($_GET, CreateTableBasedGrantsManager(), GetIdentityCheckStrategy());
