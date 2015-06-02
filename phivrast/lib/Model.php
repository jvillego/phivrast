<?php
/**
 * Clase padre de los models
 * @author jvillego
 * 
 */

class Model{
    
    protected static $DbLink;
    public static  $modelError;


    private static function addError($error){
        self::$modelError.=$error;
}


    protected static function connect($connectionTag='database'){
        
        $server = Engine::getConfigData($connectionTag, 'server');
        $username = Engine::getConfigData($connectionTag, 'username');
        $password = Engine::getConfigData($connectionTag, 'password');
        $database = Engine::getConfigData($connectionTag, 'database');
        
        if(!self::$DbLink = mysql_connect($server,$username,$password)){
            self::addError(mysql_error()); 
            self::$DbLink = null;
            return false;
        }
        if(!mysql_select_db($database, self::$DbLink)){
            self::addError(mysql_error()); 
            self::$DbLink = null;
            return false;
        }
        return true;
    }
    
    public static function isConnected(){
        if(self::$DbLink != null)
            return TRUE;
        else
            return FALSE;
    }


    public static function query($sql){
        $rs = null;
        if(!self::isConnected()){
            if(!self::connect()){ 
                return false;
            }
            $rs = mysql_query($sql, self::$DbLink) or self::addError($sql." >> ".mysql_error());
        }else
            $rs = mysql_query($sql, self::$DbLink) or self::addError($sql." >> ".mysql_error());
        
        return $rs;
    }
    
    public static function getLastId() {
        return mysql_insert_id(self::$DbLink);
    }
    
    public static function fetchAll($sql){
        $aRs = array();
        if($sql!=null){
            $rs = self::query($sql);
            while($rw = @mysql_fetch_object($rs)){
                $aRs[] = $rw;
            }
        }
        return $aRs;
    }
    
    public static function fetchOne($sql){
        $aRs = array();
        if($sql!=null){
            $rs = self::query($sql);
            $aRs[] = mysql_fetch_object($rs);
        }
        return $aRs[0];
    }
    
    
}
?>
