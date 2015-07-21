#!/usr/bin/php -q
<?php
error_reporting (E_ALL ^ E_NOTICE);


ob_implicit_flush(true);
//set_time_limit(30);


define('DS', DIRECTORY_SEPARATOR);
define('IVRPATH', dirname(__FILE__));

define('UNIQUEID', isset($argv[1])?$argv[1]:null);


include( dirname(dirname(__FILE__)).'/phivrast/lib/engine.php');

Engine::start();

exit();

?>
