<?php
namespace Turnos\UI\actions\apijson\clientes;

use Turnos\UI\components\filter\model\UIClienteCriteria;

use Turnos\UI\actions\apijson\TurnosApiJson;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\utils\ReflectionUtils;
use Rasty\exception\RastyException;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Turno;

use Rasty\i18n\Locale;


/**
 * Se obtiene el listado de pacientes.
 * 
 * @author bernardo
 * @since 15/05/2014
 */
class ClientesApiJson extends TurnosApiJson{

	
	protected function executeImpl(){

		$response = array();
		
		if( !TurnosUtils::isProfesionalLogged() ){
				throw new UserRequiredException(); 
		}

		
		$query = RastyUtils::getParamGET("query");
		
		$criteria = new UIClienteCriteria();
		$criteria->setNombre( $query );
		$criteria->addOrder("nombre", "ASC");
		$criteria->setRowPerPage( null );
		
		
		$cantidad = UIServiceFactory::getUIClienteService()->getCount( $criteria );
		$response["cantidadTotal"] = $cantidad;
		
		if($cantidad > 100){
			$criteria->setRowPerPage( 100 );
		}
		
		$clientes = UIServiceFactory::getUIClienteService()->getList( $criteria );
		

		$clientesJson = array();
		
		foreach ($clientes as $cliente) {
			
		
			$clientesJson[] = $this->buildClienteJson($cliente);
				
				
		}

		
		$response["clientes"] = $clientesJson;
		
		return $response;
		
	}
	

}
?>