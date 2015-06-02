<?php

/* *
 * De esta clase se pueden extender modelos para realizar conexiones a ws soap
 */

class SoapModel{

    private static $wsdl;
    
    public static function setWsdlUrl($wsdl) {
        self::$wsdl = $wsdl;
    }
    
    public static function __callStatic($name, $arguments) {
        //error_log("\tCALLED: \n", 3, "/tmp/error.log");
        //error_log("\tFUNCTION: $name\n", 3, "/tmp/error.log");
        //error_log("\tARGUMENTS:\n".var_export($arguments,true), 3, "/tmp/error.log");
        try{

            $soap_options = array(
                'trace'       => 1,
                'exceptions'  => 1 
            );

            //error_log("$name(".json_encode($arguments).")\n", 3, "/tmp/error.log");
            $client = new SoapClient(self::$wsdl, $soap_options);
            
            //error_log("\tMETODOS:\n".var_export($client->__getFunctions(),true), 3, "/tmp/error.log");
            //error_log("\tOBJETO SOAP CREADO:\n".var_export($client,true), 3, "/tmp/error.log");
            
            $result = $client->$name($arguments[0]);
     
            //error_log("\tTRACE:\n".var_export($client->__getLastRequest(),true), 3, "/tmp/error.log");
            //error_log("\tRESULT:\n".var_export($result,true), 3, "/tmp/error.log");

            return $result; 

        }  catch (SoapFault $e){
            error_log("SoapModel Error:\n", 3, "/tmp/error.log");
            error_log($e->getMessage()."\n", 3, "/tmp/error.log");
            return FALSE;
        }
    }
    
    
}
