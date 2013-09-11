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
use Rasty\exception\RastyDuplicatedException;
use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;
/**
 * se realiza el alta de un turno.
 * @author bernardo
 * @since 14/08/2013
 */
class AgregarTurno extends Action{

	
	public function execute(){

		$forward = new Forward();

		$page = PageFactory::build("TurnoAgregar");
		
		$turnoForm = $page->getComponentById("turnoForm");
		
		try {

			//creamos un nuevo turno.
			$turno = new Turno();
			
			//completados con los datos del formulario.
			$turnoForm->fillEntity($turno);
			
			//agregamos el turno.
			UIServiceFactory::getUITurnoService()->add( $turno );
			
			//seteamos la fecha de la agenda por la del turno dado
			TurnosUtils::setFechaAgenda( $turno->getFecha() );
			
			//lo mismo con el profesional
			TurnosUtils::setProfesionalAgenda( $turno->getProfesional() );
			
			$forward->setPageName( $turnoForm->getBackToOnSuccess() );
			$forward->addParam( "clienteOid", $turno->getCliente()->getOid() );

			$turnoForm->cleanSavedProperties();
			
		} catch (RastyDuplicatedException $e) {
		
			$forward->setPageName( "TurnoAgregar" );
			$forward->addError( $e->getMessage() );
			
			//guardamos lo ingresado en el form.
			$turnoForm->save();
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "TurnoAgregar" );
			$forward->addError( $e->getMessage() );
			
			//guardamos lo ingresado en el form.
			$turnoForm->save();
			
		}
		
		return $forward;
		
	}

}
?>