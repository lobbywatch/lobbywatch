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
include_once dirname(__FILE__) . '/components/application.php';
include_once dirname(__FILE__) . '/authorization.php';
include_once dirname(__FILE__) . '/components/page/home_page.php';
include_once dirname(__FILE__) . '/components/error_utils.php';

SetUpUserAuthorization();

try {

    $page = new HomePage(GetCurrentUserPermissionsForPage("index"), 'UTF-8');
    $renderer = new ViewRenderer($page->GetLocalizerCaptions());
    echo $renderer->Render($page);

} catch(Exception $e) {
    ShowErrorPage($e);
}
