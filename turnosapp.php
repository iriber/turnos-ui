<?php

use Turnos\UI\conf\TurnosUISetup;
use Rasty\utils\RastyUtils;
use Rasty\factory\ApplicationFactory;
use Rasty\utils\Logger;

//include('conf/init.php');

//include ( PHPRASTY_PATH . '/application.php' );

function shutdown() {
 if (($error = error_get_last())) {
   //ob_clean();
   # raport the event, send email etc.
   echo "<pre>";
   var_dump($error);
   echo "</pre>";
   # from /error-capture, you can use another redirect, to e.g. home page
  }
}

register_shutdown_function('shutdown');

session_start();

include_once   'vendor/autoload.php';

TurnosUISetup::initialize();

$type = RastyUtils::getParamGET('type') ;

$oApp = ApplicationFactory::build( $type );

$oApp->execute();


?>
