<?php
/**
 * TODO trabajar en el manejo de errores genéricos.
 * 
 * P,ej: mostrar una página linda y registrar el error: log + email.
 * 
 */

function shutdown() {
 if (($error = error_get_last())) {
   //ob_clean();
   echo "<pre>";
   var_dump($error);
   echo "</pre>";
  }
}

register_shutdown_function('shutdown');


?>
