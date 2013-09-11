<?php
namespace Turnos\UI\actions\turnos;

use Turnos\UI\components\form\turno\TurnoForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Turno;

use Rasty\actions\Action;
use Rasty\actions\Forward;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;
/**
 * se da inicio a un turno.
 * 
 * @author bernardo
 * @since 15/08/2013
 */
class IniciarTurno2 extends Action{

	
	public function execute(){

		$forward = new Forward();

		$oid = RastyUtils::getParamGET("oid");
		
		try {

			
			//iniciamos el turno.
			$turno = UIServiceFactory::getUITurnoService()->iniciar( $oid );
			
			//nos vamos a la historia clínica del paciente del turno.
			
			$forward->setPageName( "HistoriaClinica" );
			$forward->addParam("clienteOid", $turno->getCliente()->getOid() );
			
		
		} catch (\Exception $e) {
		
			$forward->setPageName( "TurnosHome" );
			$forward->addError( $e->getMessage() );
			
		}
		
		return $forward;
		
	}

}
?>