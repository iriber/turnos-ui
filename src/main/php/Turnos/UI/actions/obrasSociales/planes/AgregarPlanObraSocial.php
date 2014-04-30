<?php
namespace Turnos\UI\actions\obrasSociales\planes;

use Turnos\UI\components\form\turno\PlanObraSocialForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\PlanObraSocial;

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
 * se realiza el alta de un plan de obra social.
 * @author bernardo
 * @since 24/04/2014
 */
class AgregarPlanObraSocial extends Action{

	
	public function execute(){

		$forward = new Forward();

		$page = PageFactory::build("PlanesObraSocial");
		
		$planForm = $page->getComponentById("planObraSocialForm");
		
		$obraSocialOid = "";
		
		try {

			//Logger::log( "agregando horario");
			
			//creamos un nuevo horario.
			$plan = new PlanObraSocial();
			
			//completados con los datos del formulario.
			$planForm->fillEntity($plan);

			if($plan->getObraSocial()!=null)
				$obraSocialOid = $plan->getObraSocial()->getOid();
			
			//Logger::log( "profesional $profesionalOid" );
			
			//agregamos el plan.
			UIServiceFactory::getUIPlanObraSocialService()->add( $plan );
			
			$forward->setPageName( $planForm->getBackToOnSuccess() );
			$forward->addParam( "obraSocialOid", $obraSocialOid );

			$planForm->cleanSavedProperties();
			
		} catch (RastyDuplicatedException $e) {
		
			$forward->setPageName( "PlanesObraSocial" );
			$forward->addParam( "obraSocialOid", $obraSocialOid );
			$forward->addError( $e->getMessage() );
			
			//guardamos lo ingresado en el form.
			$planForm->save();
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "PlanesObraSocial" );
			$forward->addParam( "obraSocialOid", $obraSocialOid );
			$forward->addError(Locale::localize($e->getMessage()) );
			
			//guardamos lo ingresado en el form.
			$planForm->save();
			
		}
		
		return $forward;
		
	}

}
?>