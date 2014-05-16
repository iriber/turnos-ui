<?php
namespace Turnos\UI\actions\login;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Turno;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;
use Rasty\utils\Logger;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;

use Rasty\app\RastyMapHelper;
use Rasty\factory\ComponentFactory;
use Rasty\factory\ComponentConfig;

/**
 * se realiza el login por json.
 * @author bernardo
 * @since 08/05/2014
 */
class LoginJson extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$username = RastyUtils::getParamGET("username");
			$password = RastyUtils::getParamGET("password");
			
			Logger::log("autenticando $username $password");
			
			RastySecurityContext::login( $username, $password );
			
			$user = RastySecurityContext::getUser();
			
			if( TurnosUtils::isProfesional($user)){
				
				$profesional = UIServiceFactory::getUIProfesionalService()->getProfesionalByUser( $user );
				TurnosUtils::login( $profesional );
				
				$result["forward"] = "ProfesionalHome";
				
			}else{
				
				$result["forward"] = "TurnosHome";
			}
		
		} catch (RastyException $e) {
		
			$result["forward"] = "Login";
			$result["error"] = $e->getMessage() ;
			
		}
		
		return $result;
		
	}

}
?>