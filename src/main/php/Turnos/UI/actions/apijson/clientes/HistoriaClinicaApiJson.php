<?php
namespace Turnos\UI\actions\apijson\clientes;


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
use Turnos\Core\model\Cliente;
use Turnos\Core\model\Practica;

use Rasty\i18n\Locale;

/**
 * se obtiene la historia clínica de un 
 * paciente
 * 
 * @author bernardo
 * @since 12/05/2014
 */
class HistoriaClinicaApiJson extends TurnosApiJson{

	
	protected function executeImpl(){


		$result = array();
		

		$profesional = TurnosUtils::getProfesionalLogged();
		$nombre = $profesional->getNombre();
		
		$clienteJson = array();
		$practicasJson = array();
		
		$clienteOid = RastyUtils::getParamGET("clienteOid");
		if(!empty($clienteOid) ){

			//a partir del id buscamos el cliente.
			$cliente = UIServiceFactory::getUIClienteService()->get($clienteOid);
	
			$clienteJson = $this->buildClienteJson($cliente);
			
			$practicas = $this->getPracticas($cliente);
			
			foreach ($practicas as $practica) {
				$practicasJson[] = $this->buildPracticaJson($practica);
			}
			
		}else{
			$clienteJson = array ( "nombre" => "naranja");
		}
		
		

		$response = array();
		
		$response["historiaClinica"]["practicas"] = $practicasJson;
		$response["historiaClinica"]["cliente"] = $clienteJson;
		
		$response["profesional"] = array(
					"nombre" => $nombre
			);			
				
				
		
		return $response;
		
	}
	

	public function getPracticas(Cliente $cliente){
		
		return UIServiceFactory::getUIPracticaService()->getHistoriaClinica($cliente);
	}
	


}
?>