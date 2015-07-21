<?php
/*
 * Controlador inicial
 */

class InicioController extends ApplicationController {   

    public static function main() {
        Ivr::reproducirMensaje("Bienvenido al menú principal:
marque:
1. Para elegir x cosa
2. para otra cosa
3. para cosa final
");

$opcion = Ivr::pedirDatos(null, 2);
Ivr::reproducirMensaje("Seleccionaste la opción: $opcion");
    }
    
    public static function h() {

    }
}
?>
