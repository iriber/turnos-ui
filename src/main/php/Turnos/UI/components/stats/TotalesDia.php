<?php

namespace Turnos\UI\components\stats;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;

use Rasty\utils\XTemplate;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Turno;

use Rasty\utils\LinkBuilder;

/**
 * Totales del día.
 * 
 * @author bernardo
 * @since 01/09/2013
 */
class TotalesDia extends RastyComponent{
		
	private $fecha;
	
	public function getType(){
		
		return "TotalesDia";
		
	}

	public function __construct(){
		
		$this->setFecha( new \Datetime() );
		
	}

	protected function parseLabels(XTemplate $xtpl){
		
		$xtpl->assign("lbl_enSala",  $this->localize( "stats.totales.enSala" ) );
		$xtpl->assign("lbl_asignados",  $this->localize( "stats.totales.asignados" ) );
		$xtpl->assign("lbl_atendidos",  $this->localize( "stats.totales.atendidos" ) );
		$xtpl->assign("lbl_totalPacientes",  $this->localize( "stats.totales.totalPacientes" ) );
		$xtpl->assign("lbl_importe",  $this->localize( "stats.totales.importe" ) );
		
	}

	protected function getTurnos(){
		
		return UIServiceFactory::getUITurnoService()->getTurnosDelDia( $this->getFecha() );
		
	}

	protected function parseXTemplate(XTemplate $xtpl){
		
		/*labels de la agenda*/
		$this->parseLabels($xtpl);
		
		//buscamos los turnos dado el profesional y la fecha.
		$turnos = $this->getTurnos();
		
		$importe = 0;
		$totalPorEstado = array( 
								EstadoTurno::Asignado => 0,
								EstadoTurno::Atendido => 0,
								EstadoTurno::EnSala => 0,
								EstadoTurno::EnCurso => 0
							);
		$totalPacientes = 0;
		foreach ($turnos as $turno) {
			
			$importe += $turno->getImporte();
			$totalPacientes++;
			if( array_key_exists($turno->getEstado(), $totalPorEstado))
					$totalPorEstado[$turno->getEstado()] +=1;
				
		}

		$xtpl->assign("enSala", $totalPorEstado[EstadoTurno::EnSala] + $totalPorEstado[EstadoTurno::EnCurso] );
		$xtpl->assign("asignados", $totalPorEstado[EstadoTurno::Asignado]);
		$xtpl->assign("atendidos", $totalPorEstado[EstadoTurno::Atendido] );
		$xtpl->assign("totalPacientes", $totalPacientes );
		$xtpl->assign("importe",  TurnosUtils::formatMontoToView($importe) );
	}
	
	public function getFecha()
	{
	    return $this->fecha;
	}

	public function setFecha($fecha)
	{
	    $this->fecha = $fecha;
	}

	protected function initObserverEventType(){
		$this->addEventType( "Turno" );
	}
	
	public function setStrFecha($strFecha){
		if( !empty($strFecha) ){
			$fecha = TurnosUtils::newDateTime($strFecha) ;
			$this->setFecha($fecha);
		}
	}
}
?>