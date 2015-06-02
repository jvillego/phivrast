<?php
/**
 * En esta clase se definen funciones comunes para cualquier IVR
 * @author jvillego
 * @since 2011-10-26
 */


class Ivr{
    private static $agi;

    private static $sonidosDir;

    private static $sip_server;
    
    /**
     * Establece el objeto agi
     * @param type $agi 
     */
    public static function setAgi($agi) {
        self::$agi = $agi;
    }
    
    /**
    * Constructor
    */ 
    public static function AgiStart($conf=array()) {
        self::$sonidosDir = BASEDIR . '/sounds/';
        if(!is_object(self::$agi)){
            self::$agi = new AGI();
            self::$sip_server = isset($conf['core']['sip_server'])&&!empty($conf['core']['sip_server'])? 
                    $conf['core']['sip_server'] : '';
        }
    }
    
    
    /**
     * Devuelve el objeto AGI
     * @return object Retorna el objeto agi
     */
    public static function agi(){
        return self::$agi;
    }


    /**
     * Lee los digitos de un numero
     * @param mixed $numero 
     */
    public static function leerDigitos($numero){
        $numero = (string) $numero;
        for ($i = 0; $i < strlen($numero); $i++) {
                self::$agi->stream_file('/home/etrujilf/audios_numeros/' . $numero[$i]);
        }
    }

    /**
     * Reproduce un archivo de audio en *gsm, *sln
     * @param type $fileName nombre del archivo
     * @param type $useDefaultPath Usar ruta por defecto.  (true)
     */
    public static function reproducirSonido($fileName, $useDefaultPath = true){
        $stream = $useDefaultPath? self::$sonidosDir.$fileName : $fileName;
        $arr = glob($stream.'{*gsm,*sln}', GLOB_BRACE);
        if(count($arr)>0){
            $aux = array();
            $aux['code'] = 510;
            while($aux['code'] == 510){
                $aux = self::$agi->stream_file($stream);
            }
        }
    }

    /**
    * Obtiene los datos que digita el cliente
    * @param type $soundFileName  Sonido a reproducir
    * @param type $chars  Numero de digitos a esperar
    * @param type $timeWait Tiempo de espera
    * @return string 
    */
    public static function pedirDatos($soundFileName, $chars=1, $timeWait=6000, $useDefaultPath = true){
        $aux = array();
        $aux['code'] = 510;
        while($aux['code'] == 510){
        $aux = self::$agi->get_data(
                $useDefaultPath? self::$sonidosDir.$soundFileName : $soundFileName, $timeWait, $chars);
        }
        return $aux['result'];
    }
    
    /**
     * Traslada una llamada a un DNIS diferente
     * @param type $numero 
     */
    public static function trasladarLlamada($numero){
        //self::$agi->exec_dial("SIP",  "$numero@italtel", 60);
        self::$agi->exec("Dial",  "SIP/$numero".(!empty(self::$sip_server)?'@'.self::$sip_server:'').", 60");
    }
	
	/**
     * Termina la llamada
     */
    public static function terminarLlamada() {
        self::$agi->hangup();
    }
    
    /**
     * Permite iniciar la grabacion de la llamada
     * @param string $filename  nombre del archivo de la grabacion
     * @param type $maxduration Maxima duracion de la grabacion
     */
    public static function grabarSonido($filename=NULL, $maxduration = 5000) {
       $filename = self::$sonidosDir . ( empty($filename)? '/records/' . date('YmdHis') :   '/records/' . $filename );
       self::$agi->record_file($filename, 'WAV', '#', 5000, NULL, true);
       system("sox {$filename}.WAV -t raw -r 8000 -s -w -c 1 {$filename}.sln");
       sleep(1);
       unlink("$filename.WAV");
    }
   
    public static function _goto($context, $exten, $priority) {
        self::$agi->exec("Goto", "$context,$exten,$priority");
    }
    
    
}


?>
