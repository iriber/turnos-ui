<?php
namespace Turnos\UI\actions\turnos;

/**
 * se cambia el estado a EnSala de un turno.
 * 
 * @author bernardo
 * @since 23/08/2013
 */
use Turnos\UI\service\UIServiceFactory;

use Rasty\i18n\Locale;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;
use Rasty\actions\JsonAction;

class TurnoEnSala extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$oid = RastyUtils::getParamGET("oid");
			
			UIServiceFactory::getUITurnoService()->enSala( $oid );
			
			$result["info"] = Locale::localize("operation.successful");
			
		} catch (RastyException $e) {
		
			$result["error"] = Locale::localize("operation.fails")  ;//$e->getMessage();
			
		}
		
		return $result;
		
	}
}
?>