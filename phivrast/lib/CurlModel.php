<?php

/* *
 * De esta clase se pueden extender modelos para realizar conexiones a ws soap
 */

class CurlModel{

	private static $client = null;
	
    private static $urlBase;
    
    /**
     * Establece la url del webservice rest
     * Debe ser el primer metodo que se utiliza ya que instancia el objeto curl
     * @param unknown $url
     */
    public static function setUrl($url){
    	
    	self::$urlBase = $url;
    	self::$client = new Curl();
    }
    
    public static function getUrl(){
    	return self::$urlBase;
    }
    
    public  static function setHeader($option, $value){
    	self::$client->setopt($option, array($value));
    }
    
    public static function __callStatic($name, $arguments) {
//     	error_log("Dont stop me!", 3, "/tmp/error.log");
//         error_log("\tCALLED: \n", 3, "/tmp/error.log");
//         error_log("\tFUNCTION: $name\n", 3, "/tmp/error.log");
//         error_log("\tARGUMENTS:\n".var_export($arguments,true), 3, "/tmp/error.log");
        try{

            $curl_options = array();
            
            $result = self::$client->$name(self::$urlBase.$arguments[0], $arguments[1]);
     
            return $result; 

        }  catch (SoapFault $e){
//             error_log("SoapModel Error:\n", 3, "/tmp/error.log");
//             error_log($e->getMessage()."\n", 3, "/tmp/error.log");
            return FALSE;
        }
    }
    
    
}
