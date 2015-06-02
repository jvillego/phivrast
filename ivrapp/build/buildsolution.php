<?php

if(isset($argv[1])){
    define('DS', DIRECTORY_SEPARATOR);  //path de origen
    define('SRC_PATH', '../');  //path de origen
    define('DST_PATH', $argv[1]);  //path de destino


    echo "\nBuilding solution ... \n";

    //Copiar toda la carpeta a un destino particular
    echo "Copying solution to ".DST_PATH."...";
    recurse_copy(SRC_PATH, DST_PATH);
    echo " Ok\n";

    //Reemplazar el archivo lib/Ivr.php  por el archivo  build/Ivr.php
    echo "Building copy ... ";
    unlink(DST_PATH.DS.'lib'.DS.'Ivr.php');
    copy(DST_PATH.DS.'build'.DS.'Ivr.php', DST_PATH.DS.'lib'.DS.'Ivr.php');

    //Descomentar la linea del archivo lib/BaseRun.php
    $file = file(DST_PATH.DS.'lib'.DS.'BaseRun.php');
    unlink(DST_PATH.DS.'lib'.DS.'BaseRun.php');
    $hf = fopen(DST_PATH.DS.'lib'.DS.'BaseRun.php', 'w');
    foreach ( $file as $line){
        $fline = str_replace('//', '', $line);
        fwrite($hf, $fline);
    }
    fclose($hf);

    echo " Ok\n";
    //Eliminar: directorio bin, build, Documentacion.docx, nbproject
    echo "Cleaning ...";
    rrmdir(DST_PATH.DS.'bin');
    rrmdir(DST_PATH.DS.'build');
    unlink(DST_PATH.DS.'Documentacion.docx');
    rrmdir(DST_PATH.DS.'nbproject');
    echo " Ok\n";
    //Completado
    echo "Building process finished successfully!\n\n";
}else {
    echo "Modo de uso: >php  buildsolution.php  d:\solutionName..\n";
}

function recurse_copy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if( $file != '.svn' )
            if (( $file != '.' ) && ( $file != '..' ) ) { 
                if ( is_dir($src . '/' . $file) ) { 
                    recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
    } 
    closedir($dir); 
} 

function rrmdir($dir) { 
   if (is_dir($dir)) { 
     $objects = scandir($dir); 
     foreach ($objects as $object) { 
       if ($object != "." && $object != "..") { 
         if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
       } 
     } 
     reset($objects); 
     rmdir($dir); 
   } 
 }  

?>
