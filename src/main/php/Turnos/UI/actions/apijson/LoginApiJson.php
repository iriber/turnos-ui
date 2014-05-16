<?php
namespace Turnos\UI\actions\apijson;

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
class LoginApiJson extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$username = RastyUtils::getParamGET("username");
			$password = RastyUtils::getParamGET("password");
			
			Logger::log("autenticando $username $password");
			
			RastySecurityContext::login( $username, $password );
			
			$user = RastySecurityContext::getUser();
			
			$result["user"] = $this->buildUserJson($user);
			
			if( TurnosUtils::isProfesional($user)){
				
				$profesional = UIServiceFactory::getUIProfesionalService()->getProfesionalByUser( $user );
				TurnosUtils::login( $profesional );

				$result["profesional"] = $this->buildProfesionalJson($profesional);
			
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

		
	protected function buildUserJson($user){
		
		return array( "username" => $user->getUsername(),
						"password" => $user->getPassword(),
						"name" => $user->getName()  
					);
		
	}
	
	protected function buildProfesionalJson($profesional){
		
		return array( "nombre" => $profesional->getNombre(),
						"oid" => $profesional->getOid()
					);
		
	}
	
}
?>