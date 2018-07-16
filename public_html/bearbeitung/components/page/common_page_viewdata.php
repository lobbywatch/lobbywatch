<?php

/**
 * Data required to show basic layout
 */
class CommonPageViewData
{
    /** @var string */
    private $direction = 'ltr';

    /** @var string */
    private $contentEncoding = 'UTF-8';

    /** @var string */
    private $title;

    /** @var string */
    private $header;

    /** @var string */
    private $footer;

    /** @var string */
    private $entryPoint;

    /** @var string[] */
    private $clientSideScripts;

    /** @var int */
    private $inactivityTimeout = 0;

    public function __construct()
    {
        $this->clientSideScripts = array(
            'OnBeforeLoadEvent' => '',
            'OnAfterLoadEvent' => '',
        );
    }

    /**
     * @return string
     */
    public function getDirection() {
        return $this->direction;
    }

    /**
     * @param string $direction
     *
     * @return $this
     */
    public function setDirection($direction) {
        $this->direction = $direction;

        return $this;
    }

    /**
     * @return string
     */
    public function getContentEncoding() {
        return $this->contentEncoding;
    }

    /**
     * @param string $contentEncoding
     *
     * @return $this
     */
    public function setContentEncoding($contentEncoding) {
        $this->contentEncoding = $contentEncoding;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title) {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeader() {
        return $this->header;
    }

    /**
     * @param string $header
     *
     * @return $this
     */
    public function setHeader($header) {
        $this->header = $header;

        return $this;
    }

    /**
     * @return string
     */
    public function getFooter() {
        return $this->footer;
    }

    /**
     * @param string $footer
     *
     * @return $this
     */
    public function setFooter($footer) {
        $this->footer = $footer;

        return $this;
    }

    /**
     * @return string
     */
    public function getCustomHead() {
        return $this->customHead;
    }

    /**
     * @param string $customHead
     *
     * @return $this
     */
    public function setCustomHead($customHead) {
        $this->customHead = $customHead;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntryPoint() {
        return $this->entryPoint;
    }

    /**
     * @param string $entryPoint
     *
     * @return $this
     */
    public function setEntryPoint($entryPoint) {
        $this->entryPoint = $entryPoint;

        return $this;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public function getClientSideScript($key) {
        return $this->clientSideScripts[$key];
    }

    /**
     * @param string $key
     * @param string $clientSideScript
     *
     * @return $this
     */
    public function setClientSideScript($key, $clientSideScript) {
        $this->clientSideScripts[$key] = $clientSideScript;

        return $this;
    }

    /**
     * @return int
     */
    public function getInactivityTimeout() {
        return $this->inactivityTimeout;
    }

    /**
     * @param int $timeout
     * @return $this
     */
    public function setInactivityTimeout($timeout) {
        $this->inactivityTimeout = $timeout;

        return $this;
    }

}
