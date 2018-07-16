<?php

function generateDimensionString($height, $width) {
    $result = '';
    if ($height !== '') {
        $result .= sprintf('height = "%s"', $height);
    }
    if ($width !== '') {
        $result .= sprintf('width = "%s"', $width);
    }
    if ($result !== '') {
        $result .= ' ';
    }
    return $result;
}
