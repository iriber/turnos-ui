<?php
namespace Turnos\UI\actions\nomenclador;

use Turnos\UI\components\form\nomenclador\NomencladorForm;

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
 * se realiza la actualización de un Nomenclador.
 * 
 * @author bernardo
 * @since 20/02/2014
 */
class ModificarNomenclador extends Action{

	
	public function execute(){

		$forward = new Forward();
		
		$page = PageFactory::build("NomencladorModificar");
		
		$nomencladorForm = $page->getComponentById("nomencladorForm");
			
		$oid = $nomencladorForm->getOid();
						
		try {

			//obtenemos el Nomenclador.
			$nomenclador = UIServiceFactory::getUINomencladorService()->get($oid );
		
			//lo editamos con los datos del formulario.
			$nomencladorForm->fillEntity($nomenclador);
			
			//guardamos los cambios.
			UIServiceFactory::getUINomencladorService()->update( $nomenclador );
			
			$forward->setPageName( "Nomencladores" );
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "NomencladorModificar" );
			$forward->addError( $e->getMessage() );
			$forward->addParam("oid", $oid );
			
		}
		return $forward;
		
	}

}
?>