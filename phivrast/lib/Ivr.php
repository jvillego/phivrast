<?php
/**
 * En esta clase se definen funciones comunes para cualquier IVR
 * @author jvillego
 * @since 2011-10-26
 */
 

function pedirDatos($mensaje, $lenght=255) {
	echo "Ingrese datos: ";
        $var = fopen("php://stdin", "r");
        $line = fgets($var, $lenght+1);
        echo "\n";
	return trim($line);
}

class Ivr{
    private static $response;

    private static $sonidosDir;
    
    public static $playsounds = true;
    
    public static function setAgi($agi) {
        //Por compatibilidad esta funcion esta vacia
    }
    
    /**
    * Constructor
    */ 
    public static function AgiStart() {
        self::$sonidosDir = IVRPATH. DIRECTORY_SEPARATOR.'sounds'.DIRECTORY_SEPARATOR;
    }
    
    public static function agi(){
        //Por compatibilidad esta funcion esta vacia
    }

    /**
     * Lee los digitos de un numero
     * @param string $numero 
     */
    public static function leerDigitos($numero){
        $numero = (string) $numero;
        for ($i = 0; $i < strlen($numero); $i++) {
                echo $numero[$i];
        }
    }


    /**
    * Muestra en pantalla un mensaje o lo reproduce si se cuenta con un tts
    * @param string $mensaje mensaje a mostrar/reproducir
    * @param string $tts indica si se usara un tts (false) 
    */
    public static function reproducirMensaje($mensaje, $soundFile = null, $tts=false){
        
        if($tts){
            //write here tts functions
        }else{
            if(!empty($soundFile)){
                echo $mensaje, "\n";
                if(self::$playsounds){
                    self::reproducirSonido($soundFile);
                }
            }else{
                echo $mensaje, "\n";
            }
        }
        
    }
        
    /**
     * Reproduce un sonido ubicado en el directorio sounds/
     * @param type $mensaje  Sonido a reproducir
     * @param type $useDefaultPath  false para indicar que el sonido no esta ubicado en el directorio sounds/
     */
    public static function reproducirSonido($mensaje, $useDefaultPath = true){
        if($mensaje != 'n2t/' && $mensaje != null){
            $soundfile = glob(self::$sonidosDir.$mensaje."*");
            if(!self::$playsounds){
                echo "$mensaje\n";return;
            }
            if( count($soundfile)>0 ){
                $afile = explode('.', $soundfile[0]);
                $ext = strtolower($afile[count($afile)-1]);
                
                echo "Reproduciendo: ", $soundfile[0], "\n";
//                $apath = explode(DIRECTORY_SEPARATOR, $afile[0]);
//                $filename = $apath[count($apath)-1];
                
//                if($filename == $mensaje){
                
                $operativeSystem = Engine::getConfigData('sox', 'os');
                
                if($operativeSystem == 'windows'){
                    $command = '';
                    switch($ext){
                        case 'sl':
                        case 'sln':
                            $command = Engine::getConfigData('sox', 'cmd'). ' -t sl -r '.Engine::getConfigData('sox', 'soundrate').' -c1 -q "'.  $soundfile[0]. '" -d ';
                            break;
                        case 'gsm':
                            $command = Engine::getConfigData('sox', 'cmd'). ' -t gsm -r '.Engine::getConfigData('sox', 'soundrate').' -c1 -q "'.  $soundfile[0]. '" -d ';
                            break;
                    }
                    echo system($command);
                }elseif($operativeSystem == 'linux'){
                    $command = '';
                    
                    switch($ext){
                        case 'sl':
                        case 'sln':
                            $command = 'sox -t sl -r '.Engine::getConfigData('sox', 'soundrate').' -c1 -q "'.  $soundfile[0]. '" -d ';
                            break;
                        case 'gsm':
                            $command = 'sox -t gsm -r '.Engine::getConfigData('sox', 'soundrate').' -c1 -q "'.  $soundfile[0]. '" -d ';
                            break;
                        case 'wav':
                            $command = 'sox -c1 -q "'.  $soundfile[0]. '" -d ';
                            break;
                    }
                    echo system($command);
                }
                
                
                
            }else{
                echo " ->[Not found!]";
            }
            
        }
        echo "\n";
    }

    /**
    * Obtiene los datos que digita el cliente
    * @param string $mensaje  mensaje a reproducir
    * @param integer $chars  Numero de digitos a esperar
    * @param type $timeWait Tiempo de espera
    * @return string 
    */
    public static function pedirDatos($mensaje, $length=1, $timeWait=6000, $useDefaultPath = true){
        self::reproducirSonido($mensaje);
        $var = pedirDatos($mensaje, $length);
        return $var;
    }
    
    /**
     * Traslada una llamada a un DNIS diferente
     * @param string $numero 
     */
    public static function trasladarLlamada($numero){
        echo "Tranfiere llamada a $numero\n";
    }
    
    /**
     * Termina la llamada
     */
    public static function terminarLlamada() {
        echo "Termina la llamada\n";
    }
    
    /**
     * Realiza la grabacion de un sonido y lo almacena en el directorio sounds/records/xxxx.sln
     * @param type $filename nombre del archivo para la grabacion, quedara asi:   rac_nombre_archivo.sln
     * @param type $maxduration maxima duracion en segundos de la grabacion
     */
    public static function grabarSonido($filename, $maxduration = 5000) {
        $command = Engine::getConfigData('sox', 'cmd').
                ' -d  -t raw -r '.Engine::getConfigData('sox', 'soundrate').' "sounds/records/'.$filename.'.sln" trim 0 '.($maxduration/1000);
        system($command);
    }
    
    /**
     * Permite saltar a una extension de un contexto
     * @param string $context contexto de asterisk al que requiere saltar
     * @param string $exten extension del contexto 
     * @param string $priority prioridad
     */
    public static function jumpto($context, $exten, $priority) {
        console::log("Call Jumps To  $context/$context/$priority");
        exit();
    }
    
}

/*
 * Grabar sox -d -t raw -r 8000  outputfile.sln  trim 0 5
 */
?>
