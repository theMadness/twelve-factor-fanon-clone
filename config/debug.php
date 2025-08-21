<?php

if (getenv('MW_DEBUG')) {
    $wgDisableOutputCompression = true;
    $wgDisableSearchUpdate = true;
    $wgDebugToolbar = true;
    $wgShowDebug = true;
    $wgDevelopmentWarnings = true;
    error_reporting(-1);
    ini_set('display_errors', 1);
}
