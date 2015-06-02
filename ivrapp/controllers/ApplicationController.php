<?php

/*
 * Entorno global de la aplicacion
 * Definir aqui funciones y variables globales
 */

class ApplicationController extends CController {

    public static $behavior= null;
    
    public static function reproducirCifra($valor) {
        $on2t = new N2t($valor);
        $aCifra = explode(' ', $on2t->toText());
        foreach ($aCifra as $word){
            if(strlen(trim($word))>0)
                Ivr::reproducirSonido('n2t'.DS.$word);
        }
    }
    
}
?>
