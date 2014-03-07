<?php
namespace Turnos\UI\actions\horarios;


/**
 * se borra un Horario.
 * 
 * @author bernardo
 * @since 06/03/2014
 */
use Turnos\UI\service\UIServiceFactory;

use Rasty\i18n\Locale;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;
use Rasty\actions\JsonAction;

class BorrarHorario extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$oid = RastyUtils::getParamGET("oid");
			
			UIServiceFactory::getUIHorarioService()->delete( $oid );
			
			$result["info"] = Locale::localize("operation.successful");
			
		} catch (RastyException $e) {
		
			$result["error"] = Locale::localize("operation.fails")  ;//$e->getMessage();
			
		}
		
		return $result;
		
	}
}
?>