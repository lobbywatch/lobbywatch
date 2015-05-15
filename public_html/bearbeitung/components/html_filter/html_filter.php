<?php

abstract class HTMLFilter {
    private $tags = array();

    /*
    * @param array $tags
    */
    public function setTags($tags) {
        $this->tags = $tags;
    }

    /*
    * @return array
    */
    public function getTags() {
        return $this->tags;
    }

    /*
    * @param string $value
    * @return string
    */
    abstract public function filter($value);
}

class NullHTMLFilter extends HTMLFilter {

    public function filter($value) {
        return $value;
    }

}
