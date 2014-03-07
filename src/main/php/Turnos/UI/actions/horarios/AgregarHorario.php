<?php
namespace Turnos\UI\actions\horarios;

use Turnos\UI\components\form\turno\HorarioForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Horario;

use Rasty\actions\Action;
use Rasty\actions\Forward;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;
use Rasty\exception\RastyDuplicatedException;
use Rasty\security\RastySecurityContext;

use Rasty\utils\Logger;
use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;
/**
 * se realiza el alta de un horario.
 * @author bernardo
 * @since 06/03/2014
 */
class AgregarHorario extends Action{

	
	public function execute(){

		$forward = new Forward();

		$page = PageFactory::build("HorariosProfesional");
		
		$horarioForm = $page->getComponentById("horarioForm");
		
		$profeisonalOid = "";
		
		try {

			Logger::log( "agregando horario");
			
			//creamos un nuevo horario.
			$horario = new Horario();
			
			//completados con los datos del formulario.
			$horarioForm->fillEntity($horario);

			if($horario->getProfesional()!=null)
				$profesionalOid = $horario->getProfesional()->getOid();
			
			Logger::log( "profesional $profesionalOid" );
			
			//agregamos el horario.
			UIServiceFactory::getUIHorarioService()->add( $horario );
			
			
			$forward->setPageName( $horarioForm->getBackToOnSuccess() );
			$forward->addParam( "profesionalOid", $profesionalOid );

			$horarioForm->cleanSavedProperties();
			
		} catch (RastyDuplicatedException $e) {
		
			$forward->setPageName( "HorariosProfesional" );
			$forward->addParam( "profesionalOid", $profesionalOid );
			$forward->addError( $e->getMessage() );
			
			//guardamos lo ingresado en el form.
			$horarioForm->save();
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "HorariosProfesional" );
			$forward->addParam( "profesionalOid", $profesionalOid );
			$forward->addError(Locale::localize($e->getMessage()) );
			
			//guardamos lo ingresado en el form.
			$horarioForm->save();
			
		}
		
		return $forward;
		
	}

}
?>