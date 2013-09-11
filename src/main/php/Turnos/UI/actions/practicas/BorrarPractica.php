<?php
namespace Turnos\UI\actions\practicas;


/**
 * se borra una práctica.
 * 
 * @author bernardo
 * @since 14/08/2013
 */
use Turnos\UI\service\UIServiceFactory;

use Rasty\i18n\Locale;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;
use Rasty\actions\JsonAction;

class BorrarPractica extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$oid = RastyUtils::getParamGET("oid");
			
			UIServiceFactory::getUIPracticaService()->delete( $oid );
			
			$result["info"] = Locale::localize("operation.successful");
			
		} catch (RastyException $e) {
		
			$result["error"] = Locale::localize("operation.fails")  ;//$e->getMessage();
			
		}
		
		return $result;
		
	}
}
?>