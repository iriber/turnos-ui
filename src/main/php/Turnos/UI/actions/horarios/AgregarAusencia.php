<?php
namespace Turnos\UI\actions\horarios;

use Turnos\UI\components\form\horario\AusenciaForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Ausencia;

use Rasty\actions\Action;
use Rasty\actions\Forward;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;
use Rasty\exception\RastyDuplicatedException;


/**
 * se realiza el alta de ausencia de un profesional.
 * @author bernardo
 * @since 29/08/2013
 */
class AgregarAusencia extends Action{

	
	
	public function execute(){

		$forward = new Forward();

		$page = PageFactory::build("AusenciaAgregar");
		
		$form = $page->getComponentById("ausenciaForm");
		
		try {

			$ausencia = new Ausencia();
			
			//populamos el formulario
			$form->fillEntity($ausencia);
			
			//agregamos la ausencia.
			UIServiceFactory::getUIAusenciaService()->add( $ausencia );
			
			
			$forward->setPageName( $form->getBackToOnSuccess() );
			
			$forward->addParam( "profesionalOid", $ausencia->getProfesional()->getOid() );			
		
			$form->cleanSavedProperties();
			
		} catch (RastyDuplicatedException $e) {
		
			$forward->setPageName( "AusenciaAgregar" );
			$forward->addError( $e->getMessage() );
			
			//guardamos lo ingresado en el form.
			$form->save();
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "AusenciaAgregar" );
			$forward->addError( $e->getMessage() );
			
			//guardamos lo ingresado en el form.
			$form->save();
		}
		
		return $forward;
		
	}

}
?>