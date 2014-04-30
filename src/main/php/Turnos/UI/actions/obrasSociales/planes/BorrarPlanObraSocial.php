<?php
namespace Turnos\UI\actions\obrasSociales\planes;


/**
 * se borra un plan de obra social.
 * 
 * @author bernardo
 * @since 24/04/2014
 */
use Turnos\UI\service\UIServiceFactory;

use Rasty\i18n\Locale;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;
use Rasty\actions\JsonAction;

class BorrarPlanObraSocial extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$oid = RastyUtils::getParamGET("oid");
			
			UIServiceFactory::getUIPlanObraSocialService()->delete( $oid );
			
			$result["info"] = Locale::localize("operation.successful");
			
		} catch (RastyException $e) {
		
			$result["error"] = Locale::localize("operation.fails")  ;//$e->getMessage();
			
		}
		
		return $result;
		
	}
}
?>