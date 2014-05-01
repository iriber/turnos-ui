<?php

namespace Turnos\UI\components\agenda;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;

use Rasty\utils\XTemplate;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Turno;
use Turnos\Core\model\Prioridad;

use Rasty\utils\LinkBuilder;

/**
 * Agenda de pacientes en sala del profesional logueado.
 * 
 * @author bernardo
 * @since 30/08/2013
 */
class AgendaEnSala extends RastyComponent{
		
	private $fecha;
	
	private $profesional;
	
	
	public function getType(){
		
		return "AgendaEnSala";
		
	}

	public function __construct(){
		
		
		if(TurnosUtils::isProfesionalLogged());
			$this->setProfesional(TurnosUtils::getProfesionalLogged());
		
		$this->setFecha( new \Datetime() );
		
		
	}

	protected function parseLabels(XTemplate $xtpl){
		
		$xtpl->assign("hora_label",  $this->localize( "turno.hora" ) );
		$xtpl->assign("cliente_label",  $this->localize( "turno.cliente" ) );
		$xtpl->assign("os_label",  $this->localize( "turno.obraSocial" ) );
		$xtpl->assign("estado_label",  $this->localize( "turno.estado" ) );
		$xtpl->assign("importe_label",  $this->localize( "turno.importe" ) );

		$xtpl->assign("enSala_label", $this->localize( "turno.enSala" ) );
		$xtpl->assign("iniciar_label", $this->localize( "turno.iniciar" ) );
		$xtpl->assign("finalizar_label", $this->localize( "turno.finalizar" ) );

		$xtpl->assign("totalPacientes_label", $this->localize( "agenda.totalPacientes" ) );
		
		$xtpl->assign("linkIniciar",  LinkBuilder::getActionAjaxUrl( "IniciarTurno") );
		$xtpl->assign("linkFinalizar",  LinkBuilder::getActionAjaxUrl( "FinalizarTurno") );
		$xtpl->assign("linkEnSala",  LinkBuilder::getActionAjaxUrl( "TurnoEnSala") );
		
	}

	protected function getTurnos(){
		return UIServiceFactory::getUITurnoService()->getTurnosDelDiaEstados( $this->getFecha(), $this->getProfesional(), array( EstadoTurno::EnSala, EstadoTurno::EnCurso ));
	}

	protected function parseTurno(XTemplate $xtpl, Turno $turno){
		
		
		$xtpl->assign("hora", TurnosUtils::formatTimeToView($turno->getHora()) );
				
		$xtpl->assign("turno_css", TurnosUtils::getEstadoTurnoCss($turno->getEstado()));
			
		//mostramos la prioridad.
		$xtpl->assign("prioridad_css", TurnosUtils::getPrioridadTurnoCss($turno->getPrioridad()));
		if( $turno->getPrioridad() > 1 ){
			$xtpl->assign("prioridad_css", TurnosUtils::getPrioridadTurnoCss($turno->getPrioridad()));
			$xtpl->assign("prioridad", TurnosUtils::localize( Prioridad::getLabel($turno->getPrioridad()) ) );
			$xtpl->parse("main.turno.prioridad");
		}
		
		$duracion = $turno->getDuracion();
		if($duracion>15){
			$xtpl->assign("duracion", " ($duracion min)" );
			$xtpl->parse("main.turno.duracion");
		}
				
		$cliente = $turno->getCliente();
		if(!empty($cliente) && $cliente->getOid()>0){
					$xtpl->assign("cliente",  $turno->getCliente()->__toString() );
					$xtpl->assign("cliente_oid",  $turno->getCliente()->getOid());
					$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "HistoriaClinica" , array("backTo"=> "ProfesionalHome", "clienteOid"=> $turno->getCliente()->getOid())) );
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
			
		$observaciones = $turno->getObservaciones();
		if(!empty($observaciones)){
				$xtpl->assign("observaciones", $turno->getObservaciones());
				$xtpl->parse("main.turno.observaciones");
		}
		
		
		//TODO esto sólo cuando tengo el profesional.
		if( $this->getProfesional() != null ){
			
			if( $turno->getEstado() == EstadoTurno::EnCurso ){
				
				$xtpl->parse("main.turno.finalizar");
			}
		
			if( $turno->getEstado() == EstadoTurno::EnSala ){
				
				$xtpl->parse("main.turno.iniciar");
			}
	
			if( $turno->getEstado() == EstadoTurno::Asignado || $turno->getEstado() == EstadoTurno::Atendido){
				
				$xtpl->parse("main.turno.ensala");
			}
				
			$xtpl->parse("main.turno.editar");
				
		}
		
		

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

	public function getProfesional()
	{
	    return $this->profesional;
	}

	public function setProfesional($profesional)
	{
	    $this->profesional = $profesional;
	}

	protected function initObserverEventType(){
		$this->addEventType( "Turno" );
	}
}
?>