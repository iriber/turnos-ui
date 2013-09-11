<?php
namespace Turnos\UI\actions\turnos;


/**
 * se paso un turno a estado Asignado.
 * 
 * @author bernardo
 * @since 02/09/2013
 */
use Turnos\UI\service\UIServiceFactory;

use Rasty\i18n\Locale;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;
use Rasty\actions\JsonAction;

class TurnoAsignado extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$oid = RastyUtils::getParamGET("oid");
			
			UIServiceFactory::getUITurnoService()->asignar( $oid );
			
			$result["info"] = Locale::localize("operation.successful");
			
		} catch (RastyException $e) {
		
			$result["error"] = Locale::localize("operation.fails")  ;//$e->getMessage();
			
		}
		
		return $result;
		
	}
}
?>