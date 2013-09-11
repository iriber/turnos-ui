<?php
namespace Turnos\UI\actions\clientes;

use Turnos\UI\components\form\cliente\ClienteForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Cliente;

use Rasty\actions\Action;
use Rasty\actions\Forward;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;
use Rasty\exception\RastyDuplicatedException;


/**
 * se realiza el alta de un cliente.
 * @author bernardo
 * @since 06/08/2013
 */
class AgregarCliente extends Action{

	
	public function execute(){

		$forward = new Forward();

		$page = PageFactory::build("ClienteAgregar");
		
		$clienteForm = $page->getComponentById("clienteForm");
		
		try {

			//throw new RastyException("testeando.ando");
			
			//creamos un nuevo cliente.
			$cliente = new Cliente();
			
			//completados con los datos del formulario.
			$clienteForm->fillEntity($cliente);
			
			
			//agregamos el cliente.
			UIServiceFactory::getUIClienteService()->add( $cliente );
			
			
			$forward->setPageName( $clienteForm->getBackToOnSuccess() );
			$forward->addParam( "clienteOid", $cliente->getOid() );			
		
			$clienteForm->cleanSavedProperties();
			
		} catch (RastyDuplicatedException $e) {
		
			$clienteOid = $e->getOid();
			if( !empty($clienteOid) )
				$linkDuplicado = LinkBuilder::getPageUrl( "HistoriaClinica", array("clienteOid"=>$clienteOid) );
			
			$forward->setPageName( "ClienteAgregar" );
			$forward->addError( $e->getMessage() . $linkDuplicado);
			
			//guardamos lo ingresado en el form.
			$clienteForm->save();
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "ClienteAgregar" );
			$forward->addError( $e->getMessage() );
			
			//guardamos lo ingresado en el form.
			$clienteForm->save();
		}
		
		return $forward;
		
	}

}
?>