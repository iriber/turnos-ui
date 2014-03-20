<?php
namespace Turnos\UI\actions\historia;

use Turnos\UI\components\form\historia\ResumenHistoriaClinicaForm;

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
 * se realiza la actualización de un ResumenHistoriaClinica.
 * 
 * @author bernardo
 * @since 19/03/2014
 */
class ModificarResumenHistoriaClinica extends Action{

	
	public function execute(){

		$forward = new Forward();
		
		$page = PageFactory::build("ResumenHistoriaClinicaModificar");
		
		$resumenHistoriaClinicaForm = $page->getComponentById("resumenHistoriaClinicaForm");
			
		$oid = $resumenHistoriaClinicaForm->getOid();
						
		try {

			//obtenemos la práctica.
			$resumenHistoriaClinica = UIServiceFactory::getUIResumenHistoriaClinicaService()->get($oid );
		
			//lo editamos con los datos del formulario.
			$resumenHistoriaClinicaForm->fillEntity($resumenHistoriaClinica);
			
			//guardamos los cambios.
			UIServiceFactory::getUIResumenHistoriaClinicaService()->update( $resumenHistoriaClinica );
			
			$forward->setPageName( "HistoriaClinica" );
			$forward->addParam( "clienteOid", $resumenHistoriaClinica->getCliente()->getOid() );
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "ResumenHistoriaClinicaModificar" );
			$forward->addError( $e->getMessage() );
			$forward->addParam("oid", $oid );
			
		}
		return $forward;
		
	}

}
?>