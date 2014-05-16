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
 * @since 03/08/2013
 */
class Login extends Action{

	
	public function execute(){

		$forward = new Forward();			
		try {

			RastySecurityContext::login( RastyUtils::getParamPOST("username"), RastyUtils::getParamPOST("password") );
			
			$user = RastySecurityContext::getUser();
			
			if( TurnosUtils::isProfesional($user)){
				
				$profesional = UIServiceFactory::getUIProfesionalService()->getProfesionalByUser( $user );
				TurnosUtils::login( $profesional );
				
				$forward->setPageName( $this->getForwardProfesional() );
					
			}else{
				
				$forward->setPageName( $this->getHomeForward() );
			}
			
			
			
		
		} catch (RastyException $e) {
		
			$forward->setPageName( $this->getErrorForward() );
			$forward->addError( $e->getMessage() );
			
		}
		
		return $forward;
		
	}
	
	protected function getForwardProfesional(){
		return "ProfesionalHome";
	}

	protected function getHomeForward(){
		return "TurnosHome";
	}
	
	protected function getErrorForward(){
		return "Login";
	}
}
?>