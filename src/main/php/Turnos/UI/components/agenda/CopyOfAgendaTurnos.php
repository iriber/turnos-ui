<?php

namespace Turnos\UI\components\agenda;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;

use Rasty\utils\XTemplate;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Rasty\utils\LinkBuilder;

/**
 * Agenda de turnos de un profesional.
 * 
 * @author bernardo
 * @since 15/08/2013
 */
class AgendaTurnos extends RastyComponent{
		
	private $fecha;
	
	private $profesional;
	
	private $profesionalOid;
	
	private $strFecha;
	
	public function getType(){
		
		return "AgendaTurnos";
		
	}

	private function initParams(){
		
		$profesional = $this->getProfesional();
		if( $profesional == null ){
			
			$profesionalOid = $this->getProfesionalOid();
			if(!empty($profesionalOid) ){
				$profesional = new Profesional();
				$profesional->setOid( $profesionalOid );
				$this->setProfesional($profesional);
			}else{
				$this->setProfesional(TurnosUtils::getProfesionalAgenda());
			}
		}
		
		
		$strFecha = $this->getStrFecha();
		if( !empty($strFecha) ){
			$fecha = TurnosUtils::newDateTime($strFecha) ;
			$this->setFecha($fecha);
			
		}else{
			$this->setFecha(TurnosUtils::getFechaAgenda() );
		}
		
		TurnosUtils::setFechaAgenda($this->getFecha());
		TurnosUtils::setProfesionalAgenda($this->getProfesional());
		
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		$this->initParams();
		
		//creamos la agenda con los horarios del profesional.
		$turnosMostrar = array();
		
		
		//obtenemos los horarios en que atiende.
		$horarios = UIServiceFactory::getUIHorarioService()->getHorariosDelDia( $this->getFecha(), $this->getProfesional());

		//obtenemos las ausencias notificadas por el profesional.
		$ausencias = UIServiceFactory::getUIAusenciaService()->getAusenciasDelDia( $this->getFecha(), $this->getProfesional());
		
		
		foreach ($horarios as $horario) {

			$desde = TurnosUtils::formatTimeToView( $horario->getHoraDesde() );
			$hasta = TurnosUtils::formatTimeToView( $horario->getHoraHasta() );
			$duracion = $horario->getDuracionTurno();
			
			while( $desde <= $hasta){
			
				$turnoVacio = null;
				$turnosMostrar[$desde] = $turnoVacio;
				
				$desde = TurnosUtils::addMinutes($desde, "H:i", $duracion);
				
			}
			
		}

		//buscamos los turnos dado el profesional y la fecha.
		$turnoService = UIServiceFactory::getUITurnoService();
		$turnos = $turnoService->getTurnosDelDia( $this->getFecha(), $this->getProfesional());
		
		//rellenamos la agenda los turnos asignados.
		foreach ($turnos as $turno) {

			$turnosMostrar[TurnosUtils::formatTimeToView( $turno->getHora() )] = $turno;
		}
		
		$xtpl->assign("fecha_label", $this->localize( "agenda.fecha" ) );
		$xtpl->assign("fecha", TurnosUtils::formatDateToView( $this->getFecha(), "d/m/Y ") );
		$xtpl->assign("profesional_oid",  $this->getProfesional()->getOid() );
		
		/*labels de la agenda*/
		$xtpl->assign("hora_label",  $this->localize( "turno.hora" ) );
		$xtpl->assign("cliente_label",  $this->localize( "turno.cliente" ) );
		$xtpl->assign("os_label",  $this->localize( "turno.obraSocial" ) );
		$xtpl->assign("estado_label",  $this->localize( "turno.estado" ) );
		$xtpl->assign("importe_label",  $this->localize( "turno.importe" ) );

		$xtpl->assign("agregar_label", $this->localize( "turno.agregar" ) );
		$xtpl->assign("enSala_label", $this->localize( "turno.enSala" ) );
		$xtpl->assign("editar_label", $this->localize( "turno.editar" ) );
		$xtpl->assign("borrar_label", $this->localize( "turno.borrar" ) );
		$xtpl->assign("iniciar_label", $this->localize( "turno.iniciar" ) );
		$xtpl->assign("finalizar_label", $this->localize( "turno.finalizar" ) );
		$xtpl->assign("agregar_sobreturno_label", $this->localize( "turno.agregar_sobreturno" ) );		

		$xtpl->assign("linkModificar",  LinkBuilder::getPageUrl( "TurnoModificar") );
		$xtpl->assign("linkBorrar",  LinkBuilder::getActionAjaxUrl( "BorrarTurno") );
		$xtpl->assign("linkAgregar",  LinkBuilder::getPageUrl( "TurnoAgregar") );
		$xtpl->assign("linkIniciar",  LinkBuilder::getActionAjaxUrl( "IniciarTurno") );
		$xtpl->assign("linkFinalizar",  LinkBuilder::getActionAjaxUrl( "FinalizarTurno") );
		$xtpl->assign("linkEnSala",  LinkBuilder::getActionAjaxUrl( "TurnoEnSala") );
		
		
		
		//mostramos los turnos (asignados + vacíos)
		//los ordenamos por hora.
		ksort($turnosMostrar);		
		
							
		$primerTurnoHora = true;
		$cantidadTurnosHora = 0;							
		$horaActual = false;
		$ausenciaAnterior = null;					
		foreach ($turnosMostrar as $horaDesde => $turno) {
			
			$horaTurno = substr($horaDesde,0,2);

			$horaDatetime = new \Datetime();
			$horaDatetime->setTime(date("H", strtotime($horaDesde)), date("i", strtotime($horaDesde)), 0);
			
			$ausencia = $this->getAusencia($ausencias, $horaDatetime);
			
			$turnoDisponible = ($ausencia==null)|| ($turno != null) ;
			
			//el turno se mostrará como no disponible en caso de tener una ausencia para la fecha y horario
			//o bien puede ser que se haya dado un sobreturno.
			$templateBlockTurno = ($turnoDisponible)?"turno_disponible":"turno_no_disponible";
			
			
			if($primerTurnoHora){	
				
				$primerTurnoHora = false;
				$cantidadTurnosHora= $this->getCantidadTurnosHora($horaTurno, $turnosMostrar);
				$xtpl->assign("cantidad_turnos", $cantidadTurnosHora );
				$xtpl->assign("hora",   "$horaTurno");
				$xtpl->parse("main.turno.turno_hora");
				
			}elseif( $horaActual != $horaTurno ){

				$cantidadTurnosHora= $this->getCantidadTurnosHora($horaTurno, $turnosMostrar);
				$xtpl->assign("cantidad_turnos", $cantidadTurnosHora );
				$xtpl->assign("hora", "$horaTurno");
				$xtpl->parse("main.turno.turno_hora");
			}
			
			$horaActual = $horaTurno;
			
			$xtpl->assign("hora", $horaDesde );
			$xtpl->assign("horaEncode", urlencode($horaDesde) );
			
			//parseamos el turno.
			if( $turno == null ){
				
				//turno vacíó, link para dar de alta.
				
				//TODO chequeamos si es un horario no disponible.
				if( $turnoDisponible ){

					$params = array();
					$params["hora"] = $horaDesde; 
					$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "TurnoAgregar", $params) );
					$xtpl->assign("linkSeleccionarLabel",  $this->localize("turno.agregar") );
					
					$xtpl->assign("cliente", "");
					$xtpl->assign("estado", "");
					$xtpl->assign("obra_social", ""); //$turno->getObraSocial()->__toString());
					$xtpl->assign("importe", "" );
					$xtpl->assign("turno_css", "turno_libre" );
					$xtpl->parse("main.turno.$templateBlockTurno.agregar");
					
				}else{
					
					//mostramos las observaciones si:
					//si el anterior turno tenía fecha no disponible igual al que estamos mostrando, no mostramos las observaciones
					//porque son las mismas.

					if( $ausenciaAnterior==null || ($ausenciaAnterior!=null && $ausencia->getOid()!= $ausenciaAnterior->getOid())){
						
						$xtpl->assign("mensaje", TurnosUtils::getMensajeAusencia($ausencia) );
					}else{
						$xtpl->assign("mensaje", "");
					}
						
					$params = array();
					$params["profesionalOid"] = $this->getProfesional()->getOid(); 
					$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "AusenciaAgregar", $params) );
					$xtpl->assign("linkSeleccionarLabel",  $this->localize("ausencia.ver") );
						
				}
				
				
				//actualizamos la fecha no disponible anterior para el siguiente turno
				$ausenciaAnterior = $ausencia;
				
			}else{
				
				//turno ocupado, mostramos el paciente y las distintas opciones
				
				$xtpl->assign("turno_css", TurnosUtils::getEstadoTurnoCss($turno->getEstado()));
					
				$cliente = $turno->getCliente();
				if(!empty($cliente) && $cliente->getOid()>0){
					$xtpl->assign("cliente",  $turno->getCliente()->__toString() );
					$xtpl->assign("cliente_oid",  $turno->getCliente()->getOid());
					//$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "HistoriaClinica" , array("clienteOid"=> $turno->getCliente()->getOid())) );
					//$xtpl->assign("linkSeleccionarLabel",  $this->localize("agenda.verficha") );
				}else{
					$xtpl->assign("cliente", $turno->getNombre() );
				}	
				$xtpl->assign("linkSeleccionarTurno",   LinkBuilder::getPageUrl( "TurnoModificar" , array("oid"=> $turno->getOid())) );
				$xtpl->assign("linkSeleccionarLabel",  $this->localize("turno.editar") );
				
				$xtpl->assign("estado", EstadoTurno::getLabel($turno->getEstado()) );
				
				$os = $turno->getObraSocial();
				$os = ($os!=null)? $os->getNombre() : "-";
				$xtpl->assign("obra_social", $os );
				$xtpl->assign("nroObraSocial", $turno->getNroObraSocial() );
				
				$importe = $turno->getImporte();
				$xtpl->assign("importe", TurnosUtils::formatMontoToView($importe) );
				$xtpl->assign("turno_oid",  $turno->getOid() );
				
				if( $turno->getEstado() == EstadoTurno::EnCurso ){
				
					$xtpl->parse("main.turno.$templateBlockTurno.finalizar");
				}
		
				if( $turno->getEstado() == EstadoTurno::EnSala ){
				
					$xtpl->parse("main.turno.$templateBlockTurno.iniciar");
				}

				//if( $turno->getEstado() == EstadoTurno::Asignado || $turno->getEstado() == EstadoTurno::Atendido){
				if( $turno->getEstado() == EstadoTurno::Asignado ){
					$xtpl->parse("main.turno.$templateBlockTurno.ensala");
				}
				
				$xtpl->parse("main.turno.$templateBlockTurno.editar");
				
			}

			$xtpl->parse("main.turno.$templateBlockTurno");
			$xtpl->parse("main.turno");
		}
		
		
		//si no hay nada, no atiende. mostramos el mensaje
		if(count($turnosMostrar)==0){
			$xtpl->assign("no_atiende_msg",  $this->localize("agenda.no_atiende")  );
			$xtpl->parse("main.no_atiende");
		}
		
	}
	
	public function getAusencia( $ausencias, \DateTime $hora ){
		
		$ausenciaRes = null;
		foreach ($ausencias as $ausencia) {
			
			if( !$ausencia->horaDisponible($hora) ){
				$ausenciaRes = $ausencia;
				break;
			}
		}
		
		return $ausenciaRes;
	}
	
	public function getCantidadTurnosHora($hora, $turnosMostrar){
	
		$cantidad = 0;
		
		foreach ($turnosMostrar as $horaDesde => $turno) {
			
			if($hora == substr($horaDesde,0,2))
				$cantidad++;
		}
		
		return $cantidad;
			
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

	public function getProfesionalOid()
	{
	    return $this->profesionalOid;
	}

	public function setProfesionalOid($profesionalOid)
	{
	    $this->profesionalOid = $profesionalOid;
	}

	public function getStrFecha()
	{
	    return $this->strFecha;
	}

	public function setStrFecha($strFecha)
	{
	    $this->strFecha = $strFecha;
	}
	
	protected function initObserverEventType(){

		$this->addEventType( "Turno" );
		$this->addEventType( "Profesional" );
	}
}
?>