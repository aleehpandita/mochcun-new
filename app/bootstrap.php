<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
$GLOBALS['config'] = require __DIR__ . '/config.php';

require __DIR__ . '/helpers.php';

date_default_timezone_set('America/Cancun');
mb_internal_encoding('UTF-8');

if (config('debug')) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');
}

require __DIR__ . '/db.php';
require __DIR__ . '/seo.php';
require __DIR__ . '/layout.php';
