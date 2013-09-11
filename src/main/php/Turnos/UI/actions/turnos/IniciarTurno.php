<?php
namespace Turnos\UI\actions\turnos;

use Turnos\UI\service\UIServiceFactory;

use Rasty\i18n\Locale;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;
use Rasty\actions\JsonAction;

/**
 * se da inicio a un turno.
 * 
 * @author bernardo
 * @since 15/08/2013
 */
class IniciarTurno extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$oid = RastyUtils::getParamGET("oid");
			
			$turno = UIServiceFactory::getUITurnoService()->iniciar( $oid );
			
			$result["clienteOid"] = $turno->getCliente()->getOid();
			$result["estado"] = $turno->getEstado();
			$result["info"] = Locale::localize("operation.successful");
			
		} catch (RastyException $e) {
		
			$result["error"] = Locale::localize("operation.fails")  ;//$e->getMessage();
			
		}

		return $result;
	}

}
?>