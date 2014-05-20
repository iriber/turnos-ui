<?php
namespace Turnos\UI\actions\apijson\profesionales;

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
 * se obtiene la información de los turnos del
 * día para el profesional logueado
 * 
 * @author bernardo
 * @since 12/05/2014
 */
class TurnosDiaApiJson extends TurnosApiJson{

	
	protected function executeImpl(){

		$result = array();
	
		if( !TurnosUtils::isProfesionalLogged() ){
				throw new UserRequiredException(); 
		}
			
		$profesional = TurnosUtils::getProfesionalLogged();
		
		$strFecha = RastyUtils::getParamGET("fecha");
		if( !empty($strFecha) ){
			$fecha = TurnosUtils::newDateTime($strFecha) ;
		}else{
			$fecha = TurnosUtils::getFechaAgenda();
		}
		
		$nombre = $profesional->getNombre();
		
		$turnosDia = UIServiceFactory::getUITurnoService()->getTurnosDelDia( $fecha, $profesional );
		
		$importe = 0;
		$totalPorEstado = array( 
							EstadoTurno::Asignado => 0,
							EstadoTurno::Atendido => 0,
							EstadoTurno::EnSala => 0,
							EstadoTurno::EnCurso => 0
						);
		
		$totalPacientes = 0;
		
		$turnos = array();
		
		foreach ($turnosDia as $turno) {
			
		
			$turnoKey = $turno->getEstado();
					
			if( $turnoKey == EstadoTurno::EnCurso ){
				$turnoKey = EstadoTurno::EnSala;
			}
			
			$importe += $turno->getImporte();
			$totalPacientes++;
			if( array_key_exists($turnoKey, $totalPorEstado))
					$totalPorEstado[$turnoKey] +=1;

			
			if( !array_key_exists($turnoKey, $turnos)){
					$turnos[$turnoKey] = array();
			}		
			
			$turnos[$turnoKey][] = $this->buildTurnoJson($turno);
				
				
		}

		$response = array();
		
		$response["infoTurnos"]["totales"] = array();
		$response["infoTurnos"]["totales"]["enSala"] = $totalPorEstado[EstadoTurno::EnSala] + $totalPorEstado[EstadoTurno::EnCurso];
		$response["infoTurnos"]["totales"]["asignados"] = $totalPorEstado[EstadoTurno::Asignado];
		$response["infoTurnos"]["totales"]["atendidos"] = $totalPorEstado[EstadoTurno::Atendido];
		$response["infoTurnos"]["totales"]["totalPacientes"] = $totalPacientes;
		$response["infoTurnos"]["totales"]["importe"] = TurnosUtils::formatMontoToView($importe);
	
		$response["infoTurnos"]["turnos"] = $turnos;
		

		$response["infoTurnos"]["estados"] = array( 
				$this->buildEstadoJson(EstadoTurno::EnCurso),
				$this->buildEstadoJson(EstadoTurno::EnSala),
				$this->buildEstadoJson(EstadoTurno::Asignado),
				$this->buildEstadoJson(EstadoTurno::Atendido)
			);

		$response["profesional"] = array(
					"nombre" => $nombre
			);			
				

		$response["fecha"] = TurnosUtils::formatDateToView($fecha);			
			
		return $response;
		
	}
	

}
?>