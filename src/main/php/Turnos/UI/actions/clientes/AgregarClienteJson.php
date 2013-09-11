<?php
namespace Turnos\UI\actions\clientes;

use Turnos\UI\components\form\cliente\ClienteShortForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Cliente;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;

use Rasty\app\RastyMapHelper;
use Rasty\factory\ComponentFactory;
use Rasty\factory\ComponentConfig;

/**
 * se realiza el alta de un cliente.
 * @author bernardo
 * @since 19/08/2013
 */
class AgregarClienteJson extends JsonAction{

	
	public function execute(){

		$rasty= RastyMapHelper::getInstance();
		
		$componentConfig = new ComponentConfig();
	    $componentConfig->setId( "form" );
		$componentConfig->setType( "ClienteQuickForm" );
		
		//esto setearlo en el .rasty
	    $clienteForm = ComponentFactory::buildByType($componentConfig);
	    $clienteForm->setMethod( "POST" );
	    
		try {

			//creamos un nuevo cliente.
			$cliente = new Cliente();
			
			//completados con los datos del formulario.
			$clienteForm->fillEntity($cliente);
			
			//agregamos el cliente.
			UIServiceFactory::getUIClienteService()->add( $cliente );
			
			$result["oid"] = $cliente->getOid();
						
		} catch (RastyException $e) {
		
			$result["error"] = $e->getMessage();
		}
		
		return $result;
		
	}

}
?>