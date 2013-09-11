<?php
namespace Turnos\UI\actions\obrasSociales;

use Turnos\UI\components\filter\obraSocial\model\UIObraSocialCriteria;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;

/**
 * se consultan obras sociales x json
 * 
 * @author bernardo
 * @since 07/08/2013
 */
class ListarObrasSocialesJson extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			//TODO tomar los parámetros del post
			//utilizando el mismo formulario
			$criteria = new UIObraSocialCriteria();
			$criteria->setNombre( RastyUtils::getParamPOST("query") );
			$obrasSociales = UIServiceFactory::getUIObraSocialService()->getList( $criteria );
			
			$result["entities"] = $obrasSociales;
		
		} catch (RastyException $e) {
		
			$result["error"] = $e->getMessage();
			
		}
		
		return $result;
		
	}

}
?>