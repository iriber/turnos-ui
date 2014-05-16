<?php
namespace Turnos\UI\actions\login;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;

use Rasty\actions\Action;
use Rasty\actions\Forward;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;



/**
 * se realiza el login contra el core.
 * @author bernardo
 * @since 09/05/2014
 */
class LoginMobile extends Login{

	
	protected function getForwardProfesional(){
		return "ProfesionalHomeMobile";
	}

	protected function getHomeForward(){
		return "TurnosHome";
	}
	
	protected function getErrorForward(){
		return "LoginMobile";
	}
}
?>