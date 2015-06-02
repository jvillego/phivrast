<?php

/**
 * Esta clase es un ejemplo de uso de un modelo de tipo Soap, para realizar conexiones a webservices soap
 */


class Encuestas extends SoapModel{ 
    
    private static $idEncuesta;
    
    public static function iniciar($telefono, $ucid, $asesor, $tipo, $nombreEncuesta) {

        $wsurl = Engine::getConfigData('webservice', 'wsdl');
        //console::log("ws url: ".$wsurl);
        self::setWsdlUrl($wsurl);
        
        $params = new stdClass();
        $params->Telefono = $telefono;//ani
        $params->Ucid = $ucid;//uniqueid
        $params->Asesor = $asesor;
        $params->Tipo = $tipo;
        $params->NombreEncuesta = $nombreEncuesta;
        
        $rs = self::IniciarEncuesta($params);
        self::$idEncuesta = $rs->IniciarEncuestaResult;
        
        return self::$idEncuesta; 
        
    }
    
}
