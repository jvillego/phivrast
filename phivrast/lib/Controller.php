<?php
/*
 * Main Class of controllers
 */

class CController extends BaseRun{
    
    public static function redirect($path){
        $uri = self::parseUri($path);
        Engine::setController($uri->controller);
        Engine::setAction($uri->action);
        
        Engine::run($uri->variables);
    }
    
}

?>
