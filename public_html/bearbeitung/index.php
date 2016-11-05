<?php
// Processed by afterburner.sh


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

include_once dirname(__FILE__) . '/components/startup.php';
include_once dirname(__FILE__) . '/components/security/security_info.php';
include_once dirname(__FILE__) . '/components/page/home_page.php';
include_once dirname(__FILE__) . '/components/error_utils.php';

if (file_exists(dirname(__FILE__) . '/authorization.php')) {
    include_once dirname(__FILE__) . '/authorization.php';
    SetUpUserAuthorization();
}

try {

    $page = new HomePage(GetCurrentUserGrantForDataSource("index"), 'UTF-8');
    $page->SetHeader(GetPagesHeader());
    $page->SetFooter(GetPagesFooter());
    $page->SetShowPageList(false);
    $page->OnGetCustomTemplate->AddListener('Global_GetCustomTemplateHandler');
    $page->OnCustomHTMLHeader->AddListener('Global_CustomHTMLHeaderHandler');

    GetApplication()->SetCanUserChangeOwnPassword(!function_exists('CanUserChangeOwnPassword') || CanUserChangeOwnPassword());

    $renderer = new ViewRenderer($page->GetLocalizerCaptions());
    echo $renderer->Render($page);

} catch(Exception $e) {
    ShowErrorPage($e);
}
