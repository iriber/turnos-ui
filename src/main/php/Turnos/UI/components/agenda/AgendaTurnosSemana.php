<?php

namespace Turnos\UI\components\agenda;

use Turnos\UI\pages\turnos\agregar\TurnoAgregar;

use Turnos\UI\conf\TurnosUISetup;

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
 * Agenda de turnos semanal de un profesional.
 * 
 * @author bernardo
 * @since 07/09/2013
 */
class AgendaTurnosSemana extends RastyComponent{
		
	private $fecha;
	
	private $profesional;
	
	private $profesionalOid;
	
	private $strFecha;
	
	public function getType(){
		
		return "AgendaTurnosSemana";
		
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
	
	/**
	 * armamos la matriz de turnos
	 * 
	 *   array [ hora ] [ dia ] [ turno ] 
	 *   
	 * Enter description here ...
	 * @param unknown_type $turnosSemana
	 */
	protected function armarMatrizTurnos($turnosSemana,\Datetime $fechaDesde, \Datetime $fechaHasta){
		
		$matriz = array();
		
		
		//obtenemos los horarios en que atiende para cada fecha.
		$fecha = new \Datetime();
		$fecha->setTimestamp($fechaDesde->getTimestamp() );
		
		$horarios = array();
		while ( $fecha <= $fechaHasta ){
			$horarios[$fecha->format("Y-m-d")] = UIServiceFactory::getUIHorarioService()->getHorariosDelDia( $fecha, $this->getProfesional());
			$fecha->add(new \DateInterval('P1D'));
		}

		//obtenemos los rangos de la matriz (hora desde-hasta, y la menor duración)
		$rangosMatriz = $this->getRangosMatriz( $horarios, $turnosSemana );
		$horaDesde = $rangosMatriz[0];
		$horaHasta = $rangosMatriz[1];
		$duracionMenor = $rangosMatriz[2];
		
		
		$hora = new \DateTime();
		$hora->setTimestamp( $horaDesde->getTimestamp() );
		
		//una vez armada la matriz de horarios, la vamos rellenando con los turnos.
		while ($hora<=$horaHasta) {

			$matriz[$hora->format("H:i")] = $this->getTurnosHora($hora, $turnosSemana,$fechaDesde, $fechaHasta); //array con los turnos de cada día en ese horario.
			
			$hora->modify("$duracionMenor minutes");
		}
		
		//debemos agregarle los sobreturnos.
		$matriz = $this->agregarSobreturnos($matriz, $turnosSemana , $fechaDesde, $fechaHasta);		
		
		ksort($matriz);
		
		return $matriz;
	}
	
	/**
	 * agregamos los sobreturnos a la matriz
	 * TODO en los espacios vacíos habría que agregar algo para indicar que
	 * no es un turno disponible.
	 * 
	 * @param unknown_type $matriz
	 * @param unknown_type $turnosSemana
	 * @param \Datetime $fechaDesde
	 * @param \Datetime $fechaHasta
	 */
	private function agregarSobreturnos($matriz, $turnosSemana, \Datetime $fechaDesde, \Datetime $fechaHasta ){
		
		//si la matriz no tiene como clave la hora de la matriz, tenemos que agregar el turno.

		//primero, obtenemos los sobreturnos, los vamos agrupando por hora.
		$sobreturnos = array();
		foreach ($turnosSemana as $turno) {
			
			 $horaTurno = $turno->getHora()->format("H:i");
			 
			 if( !array_key_exists($horaTurno, $matriz) ){
			 	
			 	if( !array_key_exists($horaTurno, $sobreturnos) )	
			 		$sobreturnos[$horaTurno] = array();
			 	
			 	$sobreturnos[$horaTurno][] = $turno;	
			 }
		}
		
		foreach ($sobreturnos as $horaTurno => $turnos) {
			
			$horaMin = explode(":", $horaTurno);
			$hora = new \Datetime();
			$hora->setTime( $horaMin[0], $horaMin[1]);
			$matriz[$horaTurno] = $this->getTurnosHora($hora, $turnos,$fechaDesde, $fechaHasta, "vacio"); //array con los turnos de cada día en ese horario.
			
		}
		
		return $matriz;
	}

	/**
	 * obtenemos los turnos de cada día de la semana agrupados por la hora.
	 * por ejemplo, los turnos de las 8 para lunes a domingo. Para los días que
	 * no hay turno, se rellena con un null para completar la matriz.
	 * 
	 * @param $hora
	 * @param $turnosSemana
	 * @param $fechaDesde
	 * @param $fechaHasta
	 */
	private function getTurnosHora($hora, $turnosSemana,\Datetime $fechaDesde, \Datetime $fechaHasta, $vacio=null){
		
		$turnosHora = array();
		$turnos = array();
		
		$fecha = new \Datetime();
		$fecha->setTimestamp($fechaDesde->getTimestamp() );
		
		while ( $fecha <= $fechaHasta ){
			
			$ok = false;
			foreach ($turnosSemana as $turno) {
			
				if( $turno->getHora()->format("Hi") == $hora->format("Hi") &&
			    	$turno->getFecha()->format("Ymd") == $fecha->format("Ymd") ){
				
			    	$turnosHora[$fecha->format("Y-m-d")] = $turno;
			    	
			    	$ok = true;
			    }
			}
			if(!$ok)
				$turnosHora[$fecha->format("Y-m-d")] = $vacio;
				
			$fecha->add(new \DateInterval('P1D'));
		}
		
		return  $turnosHora;
	}

	/**
	 * obtenemos los rangos del calendario, sobre los horarios
	 * definidos por el profesional.
	 * @param unknown_type $horarios
	 */
	private function getRangosMatriz($horarios){
		
		$horaMenor = new \DateTime();
		$horaMenor->setTime(23, 59);
		
		$horaMayor = new \DateTime();
		$horaMayor->setTime(0, 0);
		
		$duracionMenor = 60;
		
		foreach ($horarios as $fecha => $horariosDia) {
			
			foreach ($horariosDia as $horario) {
				
				$horaDesde = $horario->getHoraDesde();
				$horaHasta = $horario->getHoraHasta();
				$duracion = $horario->getDuracionTurno();
				
				if( $horaMenor > $horaDesde )
					$horaMenor = $horaDesde;
					
				if( $horaMayor < $horaHasta )
					$horaMayor = $horaHasta;
	
				if( $duracionMenor > $duracion )
					$duracionMenor = $duracion;
				}			
				
		}
		
		return array ( $horaMenor, $horaMayor, $duracionMenor );
	}
	
	/**
	 * parseamos el encabezado de la semana, los días.
	 * @param XTemplate $xtpl
	 * @param \Datetime $fechaDesde
	 * @param \Datetime $fechaHasta
	 */	
	private function parseDias(XTemplate $xtpl, \Datetime $fechaDesde, \Datetime $fechaHasta){
		
		$fecha = new \Datetime();
		$fecha->setTimestamp($fechaDesde->getTimestamp() );
		
		$formatoFecha = "D j-M";
		
		while ( $fecha <= $fechaHasta ){
			
			if( TurnosUtils::fechasIguales($fecha, $this->getFecha())){

				$xtpl->assign("dia_css", "agenda_turno_fechaSeleccionada");
				
			}else{
				$params = array();
				$xtpl->assign("linkSeleccionarDia",  LinkBuilder::getPageUrl( "TurnoAgregar", $params) );
				$xtpl->assign("linkSeleccionarDiaLabel",  $this->localize("agenda.ver.dia") );
				
				$xtpl->assign("dia_css", "");
				
				
			}
			
			$xtpl->assign("dia", TurnosUtils::formatDateToView($fecha, $formatoFecha));
			$xtpl->parse("main.th_dia");			
				
			$fecha->add(new \DateInterval('P1D'));
		}
		
	}

	
	protected function parseXTemplate(XTemplate $xtpl){

		$this->initParams();
		
		$fechaSeleccionada = $this->getFecha();
		
		$fechaDesde = TurnosUtils::getFirstDayOfWeek($fechaSeleccionada);
		
		$fechaHasta = TurnosUtils::getLastDayOfWeek($fechaSeleccionada);
		//$fechaHasta->modify("+6 days");
		
		//mostramos el encabezado;
		$this->parseDias($xtpl, $fechaDesde, $fechaHasta);
		
		
		$turnosSemana = UIServiceFactory::getUITurnoService()->getTurnosSemana($fechaDesde, $fechaHasta, $this->getProfesional());
		
		$turnosMostrar = $this->armarMatrizTurnos($turnosSemana,$fechaDesde, $fechaHasta);
		

		foreach ($turnosMostrar as $hora => $turnosHora) {

			$xtpl->assign("hora", $hora);
			
			foreach ($turnosHora as $fecha => $turno) {

				//fecha es un string Y-m-d
				$fechaArray = explode("-", $fecha);
				$fechaMostrar = TurnosUtils::getNewDate($fechaArray[2], $fechaArray[1], $fechaArray[0]);
				$fechaMostrar = TurnosUtils::formatDateToView($fechaMostrar, "D j-M");
				
				if($turno==null){
						
						$xtpl->assign("turno_css", "turno_vacio_disponible");
						
						$params = array( $hora, $fechaMostrar  );
						$xtpl->assign("mensajeOculto", TurnosUtils::formatMessage( $this->localize("turno.agregar.fechaHora"), $params ));
					
						$params = array();
						$params["hora"] = $hora;
						$params["fecha"] = $fecha; 
						$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "TurnoAgregar", $params) );
						$xtpl->assign("linkSeleccionarLabel",  $this->localize("turno.agregar") );
					
						$xtpl->parse("main.hora.dia.turno_vacio_disponible");
						
						
				}elseif( is_a( $turno, get_class(new Turno()))   ) {
						
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
						$xtpl->assign("estado", $this->localize(EstadoTurno::getLabel($turno->getEstado())) );
						
						if( $turno->getEstado() == EstadoTurno::EnSala ){
				
							$xtpl->parse("main.hora.dia.turno.ensala");
							
						}elseif( $turno->getEstado() == EstadoTurno::Asignado ){
							$xtpl->parse("main.hora.dia.turno.asignado");
						}else 
							$xtpl->parse("main.hora.dia.turno.otro_estado");
				
	
						$xtpl->assign("linkSeleccionarTurno",   LinkBuilder::getPageUrl( "TurnoModificar" , array("oid"=> $turno->getOid())) );
						$xtpl->assign("linkSeleccionarLabel",  $this->localize("turno.editar") );
						
						$xtpl->parse("main.hora.dia.turno");
						
				}else{//para los espacios vacío de horarios de sobreturnos.
					
					$xtpl->assign("turno_css", "turno_sobreturno");
					$params = array( $hora, $fechaMostrar );
					$xtpl->assign("mensajeOculto", TurnosUtils::formatMessage( $this->localize("turno.agregar.fechaHora"), $params ));
					
					$params = array();
					$params["hora"] = $hora;
					$params["fecha"] = $fecha; 
					$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "SobreturnoAgregar", $params) );
					$xtpl->assign("linkSeleccionarLabel",  $this->localize("turno.agregar_sobreturno") );
					$xtpl->parse("main.hora.dia.turno_vacio_sobreturno");	
				}
								
				
				$xtpl->parse("main.hora.dia");
			};

			$xtpl->parse("main.hora");
			
		}	
		
				/*
		$horas = array("08:00", "08:30", "09:00");
		
		$dias = array( 
				TurnosUtils::getNewDate(1, 9, 2013),
				TurnosUtils::getNewDate(2, 9, 2013),
				TurnosUtils::getNewDate(3, 9, 2013),
				TurnosUtils::getNewDate(4, 9, 2013),
				TurnosUtils::getNewDate(5, 9, 2013),
				TurnosUtils::getNewDate(6, 9, 2013),
				TurnosUtils::getNewDate(7, 9, 2013)
				 );
		
		foreach ($horas as $hora) {

			$xtpl->assign("hora", $hora);
			
			foreach ($dias as $dia) {
				
				$turnoDelDiaHora = "juan";
			
				$xtpl->assign("cliente", $turnoDelDiaHora);	
				$xtpl->parse("main.hora.dia.turno");
				
				$xtpl->parse("main.hora.dia");
			};

			$xtpl->parse("main.hora");
			
		}
		
		
		*/
		
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