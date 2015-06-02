<?php

/**
 * Modelo llamadas
 */

class llamadas extends Model{
    
    public static $id_llamada = null;
    
    public static function registrar($id_ivrs) {
        $sql = "INSERT INTO llamadas (ivr_id, uniqueid, dnis, fec_inicio_llamada) VALUES ($id_ivrs, '".UNIQUEID."','".ANI."',now())";
        self::query($sql);
        self::$id_llamada = self::getLastId();
    }
    
    public static function terminarLlamada() {
        $sql = "UPDATE llamadas SET fec_fin_llamada = NOW() WHERE uniqueid = '".UNIQUEID."'";
        error_log('consulta a ejecutar esta bitch!: '. $sql. "\n",3,'/tmp/ivrsaldos.log');
        
        self::query($sql);

    }

    
}
