<?php

namespace Turnos\UI\components\turnos\enCurso;

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
 * Turnos en curso
 * 
 * @author bernardo
 * @since 24/02/2014
 */
class TurnosEnCurso extends RastyComponent{
		
	private $fecha;
	
	
	public function getType(){
		
		return "TurnosEnCurso";
		
	}

	public function __construct(){
		
		$this->setFecha( new \Datetime() );
		
	}

	protected function parseLabels(XTemplate $xtpl){
		
		$xtpl->assign("hora_label",  $this->localize( "turno.hora" ) );
		$xtpl->assign("cliente_label",  $this->localize( "turno.cliente" ) );
		$xtpl->assign("os_label",  $this->localize( "turno.obraSocial" ) );
		$xtpl->assign("estado_label",  $this->localize( "turno.estado" ) );
		$xtpl->assign("importe_label",  $this->localize( "turno.importe" ) );

		$xtpl->assign("totalPacientes_label", $this->localize( "agenda.totalPacientes" ) );
		
	}

	protected function getTurnos(){
		return UIServiceFactory::getUITurnoService()->getTurnosEnCurso( $this->getFecha() );
	}

	protected function parseTurno(XTemplate $xtpl, Turno $turno){
		
		
		$xtpl->assign("hora", TurnosUtils::formatTimeToView($turno->getHora()) );
				
		$xtpl->assign("turno_css", TurnosUtils::getEstadoTurnoCss($turno->getEstado()));
			
				
		$cliente = $turno->getCliente();
		if($cliente->getOid()>0){
					$xtpl->assign("cliente",  $turno->getCliente()->__toString() );
					$xtpl->assign("cliente_oid",  $turno->getCliente()->getOid());
					$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "HistoriaClinica" , array("backTo"=> "EstadoActual", "clienteOid"=> $turno->getCliente()->getOid())) );
					$xtpl->assign("linkSeleccionarLabel",  $this->localize("agenda.verficha") );
		}else{
					$xtpl->assign("cliente", $turno->getNombre() );
		}	
				
		$xtpl->assign("estado", $this->localize(EstadoTurno::getLabel($turno->getEstado())) );
			
		$os = $turno->getObraSocial();
		$os = ($os!=null)? $os->getNombre() : "-";
		$xtpl->assign("obra_social", $os );
		$xtpl->assign("nroObraSocial", $turno->getNroObraSocial() );
			
		$xtpl->assign("importe", TurnosUtils::formatMontoToView($turno->getImporte()) );
		$xtpl->assign("turno_oid",  $turno->getOid() );
		
		$xtpl->assign("profesional", $turno->getProfesional()->__toString() );
			
		$xtpl->parse("main.turno");
	}
	
	protected function parseXTemplate(XTemplate $xtpl){
		
		/*labels de la agenda*/
		$this->parseLabels($xtpl);
		
		//buscamos los turnos dado el profesional y la fecha.
		$turnos = $this->getTurnos();
		
		$index=0;
		$totalTurnos = count($turnos);
		foreach ($turnos as $turno) {
			
			$index++;
			$xtpl->assign("odd_css", (($index % 2) == 0)?"odd":"");
			$xtpl->assign("first_css", ($index == 1)?"first":"");
			$xtpl->assign("last_css", ($index == $totalTurnos)?"last":"");
			$this->parseTurno($xtpl, $turno);
		}

		$xtpl->assign("totalPacientes", $totalTurnos);
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
}
?>