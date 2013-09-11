<?php
namespace Turnos\UI\actions\practicas;

use Turnos\UI\components\form\practica\PracticaForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Practica;

use Rasty\actions\Action;
use Rasty\actions\Forward;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;
/**
 * se realiza el alta de una práctica.
 * 
 * @author bernardo
 * @since 15/08/2013
 */
class AgregarPractica extends Action{

	
	public function execute(){

		$forward = new Forward();

		$page = PageFactory::build("PracticaAgregar");
		
		$practicaForm = $page->getComponentById("practicaForm");
		
		try {

			//creamos una práctica.
			$practica = new Practica();
			
			//completados con los datos del formulario.
			$practicaForm->fillEntity($practica);
			
			//agregamos la prácitca.
			UIServiceFactory::getUIPracticaService()->add( $practica );
			
			$forward->setPageName( "HistoriaClinica" );
			$forward->addParam( "clienteOid", $practica->getCliente()->getOid() );
		
		} catch (\Exception $e) {
		
			$forward->setPageName( "PracticaAgregar" );
			$forward->addError( $e->getMessage() );
			
		}
		
		return $forward;
		
	}

}
?>