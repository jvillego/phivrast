<?php

/* *
 * De esta clase se pueden extender modelos para realizar conexiones a ws soap
 */

class CurlModel{

	//private static $client = null;
	
    //private static $urlBase;
    
    private static $instances = array();
    
    /**
     * Establece la url del webservice rest
     * Debe ser el primer metodo que se utiliza ya que instancia el objeto curl
     * @param unknown $url
     */
    public static function setUrl($url){

    	self::singleton()->urlBase = $url;
    	//self::$urlBase = $url;
    	//self::$client = new Curl();
    	
    	
    }
    
    public static function getUrl(){
    	//return self::$urlBase;
    	return self::singleton()->urlBase;
    }
    
    public  static function setHeader($option, $value){
    	//self::$client->setopt($option, array($value));
    	return self::singleton()->setopt($option, array($value));
    }
    
    public static function __callStatic($name, $arguments) {
    	
        try{

            $curl_options = array();
            
            //$result = self::$client->$name(self::$urlBase.$arguments[0], $arguments[1]);
            $result = self::singleton()->$name(self::singleton()->urlBase.$arguments[0], $arguments[1]);
     
            return $result; 

        }  catch (SoapFault $e){
//             error_log("SoapModel Error:\n", 3, "/tmp/error.log");
//             error_log($e->getMessage()."\n", 3, "/tmp/error.log");
            return FALSE;
        }
    }
    
    
    private static function singleton(){
    	//manejamos las instancias (singleton)
    	$cc = get_called_class();
    	if(!isset(self::$instances[$cc])){
    		self::$instances[$cc] = new Curl();
    	}
    	
    	return self::$instances[$cc];
    }
    
}

