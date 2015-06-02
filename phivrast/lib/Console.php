<?php
/**
* Esta clase permite realizar el log en un archivo
*/

class console{
	
	public static $pathFile;
	
	public static function configure($pathFile){
		
		self::$pathFile = $pathFile;
		
	}

	public static function log($mensaje){
	
		error_log($mensaje."\n", 3, self::$pathFile);
	
	}

}
