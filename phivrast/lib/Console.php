<?php
/**
* Esta clase permite realizar el log en un archivo
*/

class console{
	
	public static $pathFile;
        
        private static $logLevel;
        
        private static $format;

        const LOG_LEVE_TRACE = 1;
        const LOG_LEVE_INFO = 2;
        const LOG_LEVE_WARN = 3;
        const LOG_LEVE_ERROR = 4;
        const LOG_LEVE_LOG = 5;
        
        public static function configure($config){
		
		self::$pathFile = $config['logfile'];
                self::$format = $config['ivrname'];
                
                switch ($config['loglevel']){
                    case 'trace':
                            self::$logLevel = self::LOG_LEVE_TRACE;
                        break;
                    case 'info':
                            self::$logLevel = self::LOG_LEVE_INFO;
                        break;
                    case 'warn':
                            self::$logLevel = self::LOG_LEVE_WARN;
                        break;
                    case 'error':
                            self::$logLevel = self::LOG_LEVE_ERROR;
                        break;
                    
                }
		
	}

	public static function log($mensaje, $useFormat=false){
            
                error_log( ($useFormat? date('Y-m-d H:i:s').' - '. self::$format." - " : '') . $mensaje."\n", 3, self::$pathFile);
	
	}
        
        public static function trace($mensaje) {
            
            if(self::$logLevel == self::LOG_LEVE_TRACE){
                error_log(date('Y-m-d H:i:s').' - '.self::$format." - TRACE: ".$mensaje."\n", 3, self::$pathFile);
            }
            
        }
        
        public static function info($mensaje) {
            
            if(self::$logLevel == self::LOG_LEVE_INFO){
                error_log(date('Y-m-d H:i:s').' - '.self::$format." - INFO: ".$mensaje."\n", 3, self::$pathFile);
            }
            
        }
        
        public static function warn($mensaje) {
            
            if(self::$logLevel == self::LOG_LEVE_WARN){
                error_log(date('Y-m-d H:i:s').' - '.self::$format." - WARNING: ".$mensaje."\n", 3, self::$pathFile);
            }
            
        }
        
        public static function error($mensaje) {
            
            if(self::$logLevel == self::LOG_LEVE_ERROR){
                error_log(date('Y-m-d H:i:s').' - '.self::$format." - ERROR: ".$mensaje."\n", 3, self::$pathFile);
            }
            
        }
        

}
