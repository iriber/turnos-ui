<?php
namespace Turnos\UI\actions\historia;

use Turnos\UI\components\form\historia\ResumenHistoriaClinicaForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\ResumenHistoriaClinica;

use Rasty\actions\Action;
use Rasty\actions\Forward;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;
/**
 * se realiza el alta de un ResumenHistoriaClinica.
 * 
 * @author bernardo
 * @since 19/03/2014
 */
class AgregarResumenHistoriaClinica extends Action{

	
	public function execute(){

		$forward = new Forward();

		$page = PageFactory::build("ResumenHistoriaClinicaAgregar");
		
		$resumenHistoriaClinicaForm = $page->getComponentById("resumenHistoriaClinicaForm");
		
		try {

			//creamos un ResumenHistoriaClinica.
			$resumen = new ResumenHistoriaClinica();
			
			//completados con los datos del formulario.
			$resumenHistoriaClinicaForm->fillEntity($resumen);
			
			//agregamos la prácitca.
			UIServiceFactory::getUIResumenHistoriaClinicaService()->add( $resumen );
			
			$forward->setPageName( "HistoriaClinica" );
			$forward->addParam( "clienteOid", $resumen->getCliente()->getOid() );
		
		} catch (\Exception $e) {
		
			$forward->setPageName( "ResumenHistoriaClinicaAgregar" );
			$forward->addError( $e->getMessage() );
			
		}
		
		return $forward;
		
	}

}
?>