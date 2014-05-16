<?php
namespace Turnos\UI\actions\apijson\profesionales;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\utils\ReflectionUtils;
use Rasty\exception\RastyException;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;

use Rasty\i18n\Locale;

/**
 * se consultan profesionales de una especialidad x json
 * 
 * @author bernardo
 * @since 11/05/2014
 */
class ProfesionalHomeApiJson extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$profesional = TurnosUtils::getProfesionalLogged();
			$fecha =  new \Datetime();
			
			$nombre = $profesional->getNombre();
			
			$turnos = UIServiceFactory::getUITurnoService()->getTurnosDelDia( $fecha, $profesional );
			
			$importe = 0;
			$totalPorEstado = array( 
								EstadoTurno::Asignado => 0,
								EstadoTurno::Atendido => 0,
								EstadoTurno::EnSala => 0,
								EstadoTurno::EnCurso => 0
							);
			
			$totalPacientes = 0;
			
			$response["infoPacientes"]["pacientes"] = array();
			
			$pacientes = array();
			
			foreach ($turnos as $turno) {
				
				$importe += $turno->getImporte();
				$totalPacientes++;
				if( array_key_exists($turno->getEstado(), $totalPorEstado))
						$totalPorEstado[$turno->getEstado()] +=1;

				if( !array_key_exists($turno->getEstado(), $pacientes)){
						$pacientes[$turno->getEstado()] = array();
				}		
				$pacientes[$turno->getEstado()][] = array(
							
				);
					
					
			}
	
			$response = array();
			
			$response["infoPacientes"]["totales"] = array();
			$response["infoPacientes"]["totales"]["enSala"] = $totalPorEstado[EstadoTurno::EnSala] + $totalPorEstado[EstadoTurno::EnCurso];
			$response["infoPacientes"]["totales"]["asignados"] = $totalPorEstado[EstadoTurno::Asignado];
			$response["infoPacientes"]["totales"]["atendidos"] = $totalPorEstado[EstadoTurno::Atendido];
			$response["infoPacientes"]["totales"]["totalPacientes"] = $totalPacientes;
			$response["infoPacientes"]["totales"]["importe"] = TurnosUtils::formatMontoToView($importe);
		
			$response["profesional"] = array(
						"nombre" => $nombre
				);			

		} catch (RastyException $e) {
		
			$response["error"] = $e->getMessage();
			
		}
		
		return $response;
		
	}

}
?>