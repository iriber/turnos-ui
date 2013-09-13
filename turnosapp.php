<?php

use Turnos\UI\conf\TurnosUISetup;
use Rasty\utils\RastyUtils;
use Rasty\factory\ApplicationFactory;
use Rasty\utils\Logger;

include_once   'vendor/autoload.php';

TurnosUISetup::initialize("instituto");

$type = RastyUtils::getParamGET('type') ;

ApplicationFactory::build( $type )->execute();


/*
$oApp = ApplicationFactory::build( $type );

$oApp->execute();
*/
?>
