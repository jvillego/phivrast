<?php

/**
 * Clase para manejar las peticiones CURL
 */

class Curl{
    
    /**
     *  Se crea como atributo para que sólo haya una creación de este objeto
     *  y sea más óptima la ejecución de peticiones consecutivas
     *  i.e.: Se evita la creación del objeto por para vez que se haga una petición
     */
    private $curl;
    
    /**
     *  Opciones predeterminadas para las peticiones CURL
     */
    private $curlDefaultOptions = array(
        CURLOPT_PROXY           => false,
        CURLOPT_USERAGENT       => 'MainIvrHomesFuse',
        CURLOPT_RETURNTRANSFER  => true,
        CURLOPT_FRESH_CONNECT   => true,
        CURLOPT_HEADER          => false,
        //CURLOPT_ENCODING        => "latin1"
        //header('Content-type: text/plain; charset=utf-8');
        //CURLOPT_FOLLOWLOCATION  => true,
        //CURLOPT_REFERER         => false,
        //CURLOPT_FAILONERROR     => false,
        //CURLOPT_POST    =>  1,
         //CURLOPT_VERBOSE        => true,
         //CURLOPT_HTTPHEADER     => array("Expect: "),
         //CURLINFO_HEADER_OUT    => 1
    );
    
    public function  __construct(){
        $this->curl = curl_init();
        curl_setopt_array($this->curl, $this->curlDefaultOptions);
    }

    /**
     *  Ejecuta una petición tipo GET via CURL
     */
    public function get($url, $variables = array()) {
        $action = http_build_query(is_array($variables) && count($variables)>0 ? ($variables) : array());

        curl_setopt($this->curl, CURLOPT_URL, $url.'?'.$action);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, null);
        $this->setopt(CURLOPT_TIMEOUT, 60);

        $result = curl_exec($this->curl);
        return $result;
    }

    /**
     *  Ejecuta una petición tipo POST via CURL
     */
    public function post($url, $variables, $build_query=false){
        $action = $build_query? http_build_query($variables): $variables;
        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $action);
        $this->setopt(CURLOPT_TIMEOUT, 60);
        
        //curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Expect: "));
        //curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        /*
        curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(      
            'Expect: ',
            'Content-Type: applicationjson',
            'Content-Length: ' . strlen($action))
        );*/ 

        $result = curl_exec($this->curl);
        
        return $result;
    }
    
    /**
     *  Ejecuta una petición tipo PUT via CURL
     */
    public function put($url, $variables, $build_query=false){
        $action = $build_query? http_build_query($variables): $variables;

        curl_setopt($this->curl, CURLOPT_URL, $url);
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, $action);
        $this->setopt(CURLOPT_TIMEOUT, 60);
        
        //curl_setopt($this->curl, CURLOPT_HTTPHEADER, array("Expect: "));
        //curl_setopt($this->curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));

        $result = curl_exec($this->curl);
        return $result;
    }
    
    public function     setopt($option, $value) {
        curl_setopt($this->curl, $option, $value);
    }
    
    public function getInfo() {
        return curl_getinfo($this->curl);
    }
    
    
}

