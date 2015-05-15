<?php

include_once dirname(__FILE__) . '/' . 'html_filter.php';
include_once dirname(__FILE__) . '/' . '../../libs/kses/kses.php';

class KsesHTMLFilter extends HTMLFilter {

    public function filter($value) {
        return kses($value, $this->getTags());
    }

}
