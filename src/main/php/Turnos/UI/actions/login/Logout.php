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
 * se realiza el logout del sistema.
 * @author bernardo
 * @since 22/08/2013
 */
class Logout extends Action{

	
	public function execute(){

		$forward = new Forward();			
		try {

			RastySecurityContext::logout();
			
			$forward->setPageName( "Login" );
			
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "Login" );
			$forward->addError( $e->getMessage() );
			
		}
		
		return $forward;
		
	}

}
?>