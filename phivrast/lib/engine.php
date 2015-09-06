<?php
/*
 * Motor MVC
 * by:  jvillegasosorio@gmail.com
 */


define('SCRIPT', __FILE__);
define('BASEDIR', dirname(dirname(SCRIPT)));

include(BASEDIR . '/lib/BaseRun.php');


class Engine extends BaseRun{

    private static $config;

    private static $controller;
    private static $ocontroller;
    private static $action;
    
    public static function start($config = array(), $controller = null, $action = null, $parameters = array(), $agi = null){
	
        include_once BASEDIR . '/lib/Console.php';		
		
        self::$config = $config;
        if(self::$config['core']['execution_mode']=='local'){
            require_once BASEDIR . '/lib/Ivr.php';
            if(isset(Ivr::$playsounds)){
                Ivr::$playsounds = self::$config['development']['libivr_play_sounds'];
            }
        }elseif(self::$config['core']['execution_mode']=='asterisk'){
            require_once BASEDIR . '/lib/IvrAsterisk.php';
        }

        if($agi != null){
            Ivr::setAgi ($agi);
        }
        else Ivr::AgiStart(self::$config);
        
//	console::configure(self::$config['core']['logfile']);
        
        self::$controller =  !is_null($controller)? $controller :  self::$config['run']['defaultController'];
        self::$action = !is_null($action)? $action :  self::$config['run']['defaultAction'];
        self::$ocontroller = array();
        
        console::trace("route: ".self::$controller. '/'.self::$action);
        
        //Cargamos scripts
        include_once BASEDIR . '/lib/Curl.php';
        $modelPath = BASEDIR . '/lib/scripts/*.php';
        $models = glob($modelPath);
        foreach ($models as $model) include_once $model;
        //cargamos los models
        include_once BASEDIR . '/lib/Model.php';
        include_once BASEDIR . '/lib/SoapModel.php';
        $modelPath = IVRPATH . '/models/*.php';
        $models = glob($modelPath);
        foreach ($models as $model) {
            include_once $model;
            $aModelPath = explode('/',$model);
            $modelName = str_replace('.php', '', $aModelPath[count($aModelPath)-1]);
            
            if(method_exists($modelName, '__init')){   
                $modelName::__init();   
            }
        }
        
        self::run($parameters);
        
    }
    
    /**
     * Ejecucion para el contexto h,  el objeto AGI en este contexto no esta disponible
     * @param array $parameters
     */
    public static function h($parameters){
        self::$config = parse_ini_file( 'conf/configuration.php', true);
        
        self::$controller =  self::$config['run']['defaultController'];
        self::$action = 'h';
        self::$ocontroller = array();
        
        //Cargamos scripts
        $modelPath = BASEDIR . '/lib/scripts/*.php';
        $models = glob($modelPath);
        foreach ($models as $model) include_once $model;
        //cargamos los models
        include_once BASEDIR . '/lib/Model.php';
        $modelPath = IVRPATH . '/models/*.php';
        $models = glob($modelPath);
        foreach ($models as $model) {
            include_once $model;
            $aModelPath = explode('/',$model);
            $modelName = str_replace('.php', '', $aModelPath[count($aModelPath)-1]);
            
            if(method_exists($modelName, '__init')){   
                $modelName::__init();   
            }
        }
        
        self::run($parameters);
    }
    

    public static function setController($cname){
        self::$controller = $cname;
    }
    public static function setAction($aname){
        self::$action = $aname;
    }
    
    public static function run(){
        include_once BASEDIR . '/lib/Controller.php';
        include_once IVRPATH . '/controllers/ApplicationController.php';
        $controllerPath = IVRPATH . '/controllers/'.self::$controller.'Controller.php';
        if(file_exists($controllerPath)){
            include_once $controllerPath;
            $controllerName = self::$controller.'Controller';
            if(!@is_object(self::$ocontroller[self::$controller])){
                self::$ocontroller[self::$controller] = new $controllerName;
                $action = self::$action;
                if(method_exists(self::$ocontroller[self::$controller], self::$action)){
                    $parameters = func_get_args();
                    @call_user_method(self::$action, self::$ocontroller[self::$controller], $parameters[0]); //Deprecated function
                }else{
                    error_log("El action [".self::$controller."::".self::$action."()] no fue encontrado.\n",3,'/tmp/error.log') ; //TODO: cambiar estos metodos
                }
            }else{
                if(method_exists(self::$ocontroller[self::$controller], self::$action)){
                    $parameters = func_get_args();
                    call_user_method(self::$action, self::$ocontroller[self::$controller], $parameters[0] ); //Deprecated function
                }else{
                    error_log("El action [".self::$controller."::".self::$action."()] no fue encontrado.\n",3,'/tmp/error.log') ;
                }
            }
        }else{
            error_log("El controlador [controllers/".self::$controller."Controller.php] no fue encontrado.\n",3,'/tmp/error.log');
        }
        
    }
    
    public static function setAgi($agi) {
        
    }
    
    public static function getConfigData($section, $property){
        return isset(self::$config[$section][$property])? self::$config[$section][$property]:null;
}
}

?>

