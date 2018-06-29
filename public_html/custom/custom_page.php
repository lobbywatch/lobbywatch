<?php

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

/**
 * This file was written quick and dirty. ;-)
 */

//phpinfo();

include_once dirname(__FILE__) . '/../bearbeitung/components/utils/check_utils.php';
CheckPHPVersion();
CheckTemplatesCacheFolderIsExistsAndWritable();

include_once dirname(__FILE__) . '/../bearbeitung/phpgen_settings.php';
include_once dirname(__FILE__) . '/../bearbeitung/database_engine/mysql_engine.php';
include_once dirname(__FILE__) . '/../bearbeitung/authorization.php';

include_once dirname(__FILE__) . '/../bearbeitung/components/startup.php';
include_once dirname(__FILE__) . '/../bearbeitung/components/application.php';

include_once dirname(__FILE__) . '/../bearbeitung/components/page/home_page.php';

function GetConnectionOptions() {
    $result = GetGlobalConnectionOptions();
    $result['client_encoding'] = 'utf8';
    GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
    return $result;
}

function setHTTPHeader() {
  header('Pragma: public');
  header('Cache-Control: max-age=0');
  header('Content-Type: text/html; charset=utf-8');
}


function getPageList() {
  $page = new HomePage(GetCurrentUserPermissionSetForDataSource("index"), 'UTF-8');
  $page->SetHeader(GetPagesHeader());
  $page->SetFooter(GetPagesFooter());
  $page->setBanner(GetHomePageBanner());
  $page->SetShowPageList(false);
  $page->OnGetCustomTemplate->AddListener('Global_GetCustomTemplateHandler');
  $page->OnCustomHTMLHeader->AddListener('Global_CustomHTMLHeaderHandler');
  return PageList::createForPage($page);
}

// Main

SetUpUserAuthorization(GetApplication());

try {
    if (!GetApplication()->IsCurrentUserLoggedIn()) {
      ShowErrorPage(new Exception('Not logged in.<br><br>Please <a href="login.php">log in</a>.'));
      exit(1);
    }
} catch(Exception $e) {
    ShowErrorPage($e);
}
