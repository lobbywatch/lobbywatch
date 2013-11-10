<?php

include_once dirname(__FILE__) . '/' . '../../libs/less_php/lessc.inc.php';

function autoCompileLess($inputFile, $outputFile) {
    // load the cache
    $cacheFile = $inputFile . ".cache";

    if (file_exists($cacheFile)) {
        $cache = unserialize(file_get_contents($cacheFile));
    } else {
        $cache = $inputFile;
    }

    $less = new lessc;
    $newCache = $less->cachedCompile($cache);

    if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
        file_put_contents($cacheFile, serialize($newCache));
        file_put_contents($outputFile, $newCache['compiled']);
    }
}
