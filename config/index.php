<?php
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->usePutenv(true);
$dotenv->load(__DIR__.'/../.env');

require_once 'core.php';
require_once 'branding.php';
require_once 'skins.php';
require_once 'extensions.php';
require_once 'niche.php';
require_once 'debug.php';
