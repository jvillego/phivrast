#!/usr/bin/php -q
<?php
/****************************************************************************************************************
* Arranque en modo h (contexto h)
* Incluya en su controlador por defecto un mEtodo estAtico (o action) con el nombre  h
* Recuerde que en ese contexto no estA disponible el objeto AGI, por ende no podrA reproducir o grabar sonidos 
*****************************************************************************************************************/
error_reporting (E_ALL ^ E_NOTICE);


ob_implicit_flush(true);
//set_time_limit(30);
define('DS', DIRECTORY_SEPARATOR);
define('IVRPATH', dirname(__FILE__));

define('UNIQUEID', isset($argv[1])?$argv[1]:null);


include( dirname(__FILE__).'/../phivrast/lib/engine.php');

Engine::h();

exit(0);

?>
