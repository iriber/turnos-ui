<?php
namespace Turnos\UI\actions\clientes;

use Turnos\UI\components\form\cliente\ClienteForm;

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
 * se realiza la actualización de un cliente.
 * 
 * @author bernardo
 * @since 07/08/2013
 */
class ModificarCliente extends Action{

	
	public function execute(){

		$forward = new Forward();
		
		$page = PageFactory::build("ClienteModificar");
		
		$clienteForm = $page->getComponentById("clienteForm");
			
		$oid = $clienteForm->getOid();
						
		try {

			//obtenemos el cliente.
			$cliente = UIServiceFactory::getUIClienteService()->get($oid );
		
			//lo editamos con los datos del formulario.
			$clienteForm->fillEntity($cliente);
			
			//guardamos los cambios.
			UIServiceFactory::getUIClienteService()->update( $cliente );
			
			$forward->setPageName( $clienteForm->getBackToOnSuccess() );
			$forward->addParam( "clienteOid", $cliente->getOid() );
			
			$clienteForm->cleanSavedProperties();
			
		} catch (RastyException $e) {
		
			$forward->setPageName( "ClienteModificar" );
			$forward->addError( $e->getMessage() );
			$forward->addParam("oid", $oid );
			
			//guardamos lo ingresado en el form.
			$clienteForm->save();
			
		}
		return $forward;
		
	}

}
?>