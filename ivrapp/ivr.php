#!/usr/bin/php -q
<?php
error_reporting (E_ALL ^ E_NOTICE);


ob_implicit_flush(true);

define('DS', DIRECTORY_SEPARATOR);
define('IVRPATH', dirname(__FILE__));

$phivrast_dir = dirname(dirname(__FILE__));
$framework = $phivrast_dir.'/phivrast/lib/engine.php';

if(file_exists($framework)){
    include( $framework );
    include $phivrast_dir . '/phivrast/lib/Console.php';
    
    
    $config = parse_ini_file( IVRPATH.'/conf/configuration.php', true);
    $config['core']['ivrname'] = $config['core']['ivrname']. '-' .UNIQUEID;
    console::configure($config['core']);
    
    console::log("\n\n----------------------------------------------",false);
    console::trace('STARTING IVR '. $config['core']['ivrname']);
    

    define('UNIQUEID', isset($argv[1])?$argv[1]:null);

    console::trace("ARGUMENTS: ".json_encode($argv));

    
    Engine::start($config);
}else{
    error_log("IVR ERROR: PHIVRAST FRAMEWORK NOT FOUND!");
}

exit();