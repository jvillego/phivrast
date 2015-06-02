<?php
$phpAgiPath = '/var/lib/asterisk/agi-bin/phpagi/phpagi.php';
if(@file_exists($phpAgiPath))
    require_once($phpAgiPath);

class BaseRun {
    
    protected static function parseUri($path){
        
        $parts = explode('/', $path);
        $URI = array(
            'controller'=>$parts[0],
            'action'=>$parts[1]
        );
        $vars = array();
        for($i = 2; $i < count($parts); $i+=2){
            $vars[$parts[$i]] = $parts[$i+1];
        }
        $URI['variables'] = $vars;
        return (object) $URI;
    }
    
}
?>
