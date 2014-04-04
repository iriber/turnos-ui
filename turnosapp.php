<?php

session_set_cookie_params(0, "turnos_ui" );
session_start ();

use Turnos\UI\conf\TurnosUISetup;
use Rasty\utils\RastyUtils;
use Rasty\factory\ApplicationFactory;
use Rasty\utils\Logger;
use Rasty\security\RastySecurityContext;

include_once   'vendor/autoload.php';

if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 7200)) {
	RastySecurityContext::logout();
}

$_SESSION['LAST_ACTIVITY'] = time();

TurnosUISetup::initialize("turnos_ui");

$type = RastyUtils::getParamGET('type') ;

ApplicationFactory::build( $type )->execute();


/*
$oApp = ApplicationFactory::build( $type );

$oApp->execute();
*/
?>
