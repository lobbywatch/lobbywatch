<?php

include_once dirname(__FILE__) . '/common_page.php';
include_once dirname(__FILE__) . '/../renderers/template_renderer.php';
include_once dirname(__FILE__) . '/../../components/utils/link_builder.php';
include_once dirname(__FILE__) . '/../../components/common_utils.php';

/**
 * @param CommonPage $page
 * @return string
 */
function RenderAccountVerificationMailSubject($page) {
    $customParams = array();
    $template = $page->GetCustomTemplate(PagePart::Mail, PageMode::MailVerificationSubject, 'mail/user_verification_subject.tpl', $customParams);

    $templateRenderer = GetTemplateRenderer();
    return $templateRenderer->render($template, $customParams);
}

/**
 * @param CommonPage $page
 * @param string $username
 * @param string $token
 * @return string
 */
function RenderAccountVerificationMailBody($page, $username, $token) {
    $customParams = array();
    $template = $page->GetCustomTemplate(PagePart::Mail, PageMode::MailVerificationBody, 'mail/user_verification_body.tpl', $customParams);

    $params = array_merge(
        $customParams,
        array(
            'UserName' => $username,
            'UserVerificationLink' => GetAccountVerificationLink($token),
            'SiteURL' => GetSiteURL()
        )
    );

    $templateRenderer = GetTemplateRenderer();
    return $templateRenderer->render($template, $params);
}

/**
 * @param string $token
 * @return string
 */
function GetAccountVerificationLink($token) {
    $linkBuilder = new LinkBuilder(GetSiteURL() . 'register.php');
    $linkBuilder->AddParameter('token', $token);
    return $linkBuilder->GetLink();
}
