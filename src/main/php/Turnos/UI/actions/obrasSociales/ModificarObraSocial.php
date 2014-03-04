<?php
namespace Turnos\UI\actions\obrasSociales;

use Turnos\UI\components\form\obraSocial\ObraSocialForm;

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
 * se realiza la actualización de un ObraSocial.
 * 
 * @author bernardo
 * @since 20/02/2014
 */
class ModificarObraSocial extends Action{

	
	public function execute(){

		$forward = new Forward();
		
		$page = PageFactory::build("ObraSocialModificar");
		
		$obraSocialForm = $page->getComponentById("obraSocialForm");
			
		$oid = $obraSocialForm->getOid();
						
		try {

			//obtenemos la ObraSocial.
			$obraSocial = UIServiceFactory::getUIObraSocialService()->get($oid );
		
			//lo editamos con los datos del formulario.
			$obraSocialForm->fillEntity($obraSocial);
			
			//guardamos los cambios.
			UIServiceFactory::getUIObraSocialService()->update( $obraSocial );
			
			$forward->setPageName( "ObrasSociales" );
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "ObraSocialModificar" );
			$forward->addError( $e->getMessage() );
			$forward->addParam("oid", $oid );
			
		}
		return $forward;
		
	}

}
?>