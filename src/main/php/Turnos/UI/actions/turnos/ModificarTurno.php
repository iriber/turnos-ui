<?php
namespace Turnos\UI\actions\turnos;

use Turnos\UI\components\form\turno\TurnoForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;

use Rasty\actions\Action;
use Rasty\actions\Forward;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\factory\ComponentConfig;
use Rasty\factory\ComponentFactory;

use Rasty\i18n\Locale;

use Rasty\factory\PageFactory;

/**
 * se realiza la actualización de un turno.
 * 
 * @author bernardo
 * @since 14/08/2013
 */
class ModificarTurno extends Action{

	
	public function execute(){

		$forward = new Forward();
		
		$page = PageFactory::build("TurnoModificar");
		
		$turnoForm = $page->getComponentById("turnoForm");
			
		$oid = $turnoForm->getOid();
						
		try {

			//obtenemos el turno.
			$turno = UIServiceFactory::getUITurnoService()->get($oid );
		
			//lo editamos con los datos del formulario.
			$turnoForm->fillEntity($turno);
			
			//guardamos los cambios.
			UIServiceFactory::getUITurnoService()->update( $turno );
			
			//seteamos la fecha de la agenda por la del turno dado
			TurnosUtils::setFechaAgenda( $turno->getFecha() );
			
			//lo mismo con el profesional
			TurnosUtils::setProfesionalAgenda( $turno->getProfesional() );
			
			$forward->setPageName( $turnoForm->getBackToOnSuccess() );
			$forward->addParam( "clienteOid", $turno->getCliente()->getOid() );
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "TurnoModificar" );
			$forward->addError( $e->getMessage() );
			$forward->addParam("oid", $oid );
			
		}
		return $forward;
		
	}

}
?>