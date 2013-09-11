<?php
namespace Turnos\UI\actions\practicas;

use Turnos\UI\components\form\turno\PracticaForm;

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
 * se realiza la actualización de una práctica.
 * 
 * @author bernardo
 * @since 14/08/2013
 */
class ModificarPractica extends Action{

	
	public function execute(){

		$forward = new Forward();
		
		$page = PageFactory::build("PracticaModificar");
		
		$practicaForm = $page->getComponentById("practicaForm");
			
		$oid = $practicaForm->getOid();
						
		try {

			//obtenemos la práctica.
			$practica = UIServiceFactory::getUIPracticaService()->get($oid );
		
			//lo editamos con los datos del formulario.
			$practicaForm->fillEntity($practica);
			
			//guardamos los cambios.
			UIServiceFactory::getUIPracticaService()->update( $practica );
			
			$forward->setPageName( "HistoriaClinica" );
			$forward->addParam( "clienteOid", $practica->getCliente()->getOid() );
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "PracticaModificar" );
			$forward->addError( $e->getMessage() );
			$forward->addParam("oid", $oid );
			
		}
		return $forward;
		
	}

}
?>