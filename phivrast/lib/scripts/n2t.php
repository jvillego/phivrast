<?php

/**
 * Number to text  
 * @author jvillegasosorio@gmail.com
 * @license GPL
 * @todo Se puede mejorar mucho la script
 */

class N2t{
    
    private $strNumber;
    private $aNumber;
    private $ranges = array(
        '','mil',
        'millon','mil',
        'billon','mil',
        'trillon','mil',
        'cuatrillon','mil',
        'quitillon','mil',
        'sextillon','mil',
        'septillon','mil',
        'octillon','mil',
        'nonillon','mil',
        'decillon'
    );
    private $_texts = array(
        0 => array('cero'),
        1 => array('cero','uno', 'dos', 'tres', 'cuatro', 'cinco','seis', 'siete', 'ocho', 'nueve'),
        2 => array(
            1 => array('dies', 'once','doce','trece','catorce','quince','dieciseis','diecisiete','dieciocho','diecinueve'),
            2 => array('veinte', 'veintiuno','veintidos','veintitres','veinticuatro','veinticinco','veintiseis',
                'veintisiete','veintiocho','veintinueve'),
            'treinta', 'cuarenta','cincuenta','sesenta','setenta','ochenta','noventa'
        ),
        3 => array('cien','docientos','trecientos','cuatrocientos','quinientos','seiscientos','setecientos','ochocientos','novecientos'),
    );
    

    public function __construct($number = 0){
        $this->setNumber($number);
    }
    
    public function setNumber($number = 0){
        if(is_numeric($number)){
            $this->strNumber = (string) $number;
        }
        return $this;
    }

    public function toText(){
        $strLenNum = strlen($this->strNumber);
        if($strLenNum > 3){
            $rCent =  $strLenNum / 3;
            $centenas = intval($rCent);
            $decenas = round(($rCent - $centenas) * 3);
            $sDex = substr($this->strNumber, 0, $decenas);
            $sCent = substr($this->strNumber, $decenas, $strLenNum);
            $this->aNumber = str_split($sCent, 3);

            if($sDex != null)
                array_unshift ($this->aNumber, $sDex);
        }else {
            if(intval($this->strNumber) == 0)
                return 'cero';
            $this->aNumber = array($this->strNumber);
        }
        
        $text = null;
        $i = 0;
//        echo json_encode($this->aNumber);
        $incprox = false;
        foreach ($this->aNumber as $key=>$part){
            $r = count($this->aNumber) - 1 - $key;
            
            if($part != 0  ||  $incprox){
                if($part != 0){
                    if(!($part == 1 && $this->ranges[ $r ] == 'mil')){
                        if($part == 1 && $this->strNumber>1) $text.= 'un ';
                        else $text .=$this->centenas($part).' ';
                    }    
                 }
                $text.= $this->ranges[ $r ] . 
                        ( (( $part >1 && $this->ranges[ $r ]!='mil' && $r >0) || $incprox && $r >0 )? 'es':'' ) .  ' ';
                
                $incprox = false;
                if($this->ranges[ $r ]=='mil') $incprox = true;
            }
        }
//        return ucfirst($text);
        return $text;
    }
    
    private function unidades($valor){
        return  $this->_texts[1][(integer)$valor];
    }
    
    private function decenas($valor){
        $rtxt = '';
        if($valor<10)
            return $this->unidades($valor);
        else{
            $valor  = (string) intval($valor);
            if( $valor[0] ==1  )
                return $this->_texts[2][1][(string)$valor[1]];
            elseif( $valor[0] ==2  )
                return $this->_texts[2][2][(string)$valor[1]];
            else{
                $rtxt = $this->_texts[2][(string)$valor[0]];
                if($valor[1]!=0){
                    $rtxt.= ' y ';
                    $rtxt.= $this->unidades($valor[1]);
                }
            }
        }
        return  $rtxt;
    }
    
    private function centenas($valor){
        $rtxt = '';
        $valor = (string) $valor;
        if($valor != 0 || strlen($valor) == 1)
            if($valor<100)
                return $this->decenas($valor);
            else{
                $cen =(string)$valor[0];
                $rtxt = $this->_texts[3][$cen -1];
                $dec =substr((string)$valor, -2);
                if($dec != 0){
                    if($cen == 1)
                        $rtxt.= 'to ';
                    $rtxt.= ' '. $this->decenas($dec);
                }
            }
        return $rtxt;
    }
}

?>