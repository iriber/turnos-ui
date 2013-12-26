<?php

namespace Turnos\UI\components\agenda;

use Turnos\UI\components\agenda\helper\AgendaSemanalHelper;

use Turnos\UI\components\agenda\helper\AgendaDiariaHelper;

use Turnos\UI\components\agenda\helper\AgendaHelper;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;
use Rasty\utils\LinkBuilder;
use Rasty\utils\XTemplate;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Turno;


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

	private $tipoAgenda;

	const AGENDA_DIARIA = "diaria";
	const AGENDA_SEMANAL = "semanal";
	
	public function getType(){
		
		return "AgendaTurnos";
		
	}

	private function initParams(){
		
		$profesional = $this->getProfesional();
		if( $profesional == null ){
			
			$profesionalOid = $this->getProfesionalOid();
			if(!empty($profesionalOid) ){
				
				//$profesional->setOid( $profesionalOid );
				$profesional =  UIServiceFactory::getUIProfesionalService()->get($profesionalOid) ;
				
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

		$tipoAgenda = $this->getTipoAgenda();
		if( empty($tipoAgenda) ){
			$this->setTipoAgenda(TurnosUtils::getTipoAgenda());
		}
		
		TurnosUtils::setFechaAgenda($this->getFecha());
		TurnosUtils::setProfesionalAgenda($this->getProfesional());
		TurnosUtils::setTipoAgenda($this->getTipoAgenda());
	}
	
	
	protected function parseXTemplate(XTemplate $xtpl){
		
		$this->initParams();
		
		if( $this->getTipoAgenda() == self::AGENDA_DIARIA)
				
			AgendaDiariaHelper::parseAgenda($xtpl, $this->getProfesional(), $this->getFecha());
				
		elseif($this->getTipoAgenda() == self::AGENDA_SEMANAL){
			
			 AgendaSemanalHelper::parseAgenda2($xtpl, $this->getProfesional(), $this->getFecha());
				
		}else{
			
			AgendaSemanalHelper::parseAgenda2($xtpl, $this->getProfesional(), $this->getFecha());
		}
		
		$xtpl->assign("tipoAgenda", $this->getTipoAgenda());
	}
	
	public function getTipoAgenda()
	{
	    return $this->tipoAgenda;
	}

	public function setTipoAgenda($tipoAgenda)
	{
	    $this->tipoAgenda = $tipoAgenda;
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
		$this->addEventType( "TipoAgenda" );
	}
	
	/*
	protected function parseAgendaDiaria(XTemplate $xtpl){

		$this->initParams();
		
		//obtenemos los horarios en que atiende.
		$horarios = UIServiceFactory::getUIHorarioService()->getHorariosDelDia( $this->getFecha(), $this->getProfesional());

		//obtenemos las ausencias notificadas por el profesional.
		$ausencias = UIServiceFactory::getUIAusenciaService()->getAusenciasDelDia( $this->getFecha(), $this->getProfesional());
		
				
		//creamos la agenda con los horarios del profesional.
		$turnosMostrar = array();
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
		
		
		//parseamos textos.
		$this->parseLabels( $xtpl );
		
		
		//mostramos los turnos (asignados + vacíos)
		//los ordenamos por hora.
		ksort($turnosMostrar);		
		
							
		$primerTurnoHora = true;
		$cantidadTurnosHora = 0;							
		$horaActual = false;
		$ausenciaAnterior = null;

		$index=0;
		$totalTurnos = count($turnosMostrar);
		
		foreach ($turnosMostrar as $horaDesde => $turno) {
			
			$index++;
			
			$horaTurno = substr($horaDesde,0,2);

			$horaDatetime = new \Datetime();
			$horaDatetime->setTime(date("H", strtotime($horaDesde)), date("i", strtotime($horaDesde)), 0);
			
			$ausencia = $this->getAusencia($ausencias, $horaDatetime);
			
			$turnoDisponible = ($ausencia==null)|| ($turno != null) ;
			
			//el turno se mostrará como no disponible en caso de tener una ausencia para la fecha y horario
			//o bien puede ser que se haya dado un sobreturno.
			$templateBlockTurno = ($turnoDisponible)?"turno_disponible":"turno_no_disponible";
			
			
			$xtpl->assign("hora", $horaDesde );
			$xtpl->assign("horaEncode", urlencode($horaDesde) );
			
			$xtpl->assign("odd_css", (($index % 2) == 0)?"odd":"");
			$xtpl->assign("first_css", ($index == 1)?"first":"");
			$xtpl->assign("last_css", ($index == $totalTurnos)?"last":"");
				
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

					$xtpl->parse("main.turno.$templateBlockTurno.libre");
				}else{
					
					//mostramos las observaciones si:
					//si el anterior turno tenía fecha no disponible igual al que estamos mostrando, no mostramos las observaciones
					//porque son las mismas.

					if( $ausenciaAnterior==null || ($ausenciaAnterior!=null && $ausencia->getOid()!= $ausenciaAnterior->getOid())){
						
						$xtpl->assign("mensaje", TurnosUtils::getMensajeAusencia($ausencia) );
					}else{
						$xtpl->assign("mensaje", "");
					}
					$xtpl->assign("turno_css", "turno_nodisponible");
					$xtpl->assign("odd_css", "nodisponible");	
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
				
				$xtpl->assign("estado", $this->localize(EstadoTurno::getLabel($turno->getEstado())) );
				
				$os = $turno->getObraSocial();
				$os = ($os!=null)? $os->getNombre() : "-";
				$xtpl->assign("obra_social", $os );
				$xtpl->assign("nroObraSocial", $turno->getNroObraSocial() );
				
				$importe = $turno->getImporte();
				$xtpl->assign("importe", TurnosUtils::formatMontoToView($importe) );
				$xtpl->assign("turno_oid",  $turno->getOid() );
				
				if( $turno->getEstado() == EstadoTurno::EnSala ){
				
					$xtpl->parse("main.turno.$templateBlockTurno.ensala");
					
				}elseif( $turno->getEstado() == EstadoTurno::Asignado ){
					$xtpl->parse("main.turno.$templateBlockTurno.asignado");
				}else 
					$xtpl->parse("main.turno.$templateBlockTurno.otro_estado");
		
								
				$xtpl->parse("main.turno.$templateBlockTurno.editar");
				
				$xtpl->parse("main.turno.$templateBlockTurno.ocupado");
			}

			$xtpl->parse("main.turno.$templateBlockTurno");
			$xtpl->parse("main.turno");
		}
		
		
		//si no hay nada, no atiende. mostramos el mensaje
		if(count($turnosMostrar)==0){
			$xtpl->assign("no_atiende_msg",  $this->localize("agenda.no_atiende")  );
			$xtpl->parse("main.no_atiende");
		}
		
	}*/
	
	/*
	protected function parseLabels(XTemplate $xtpl){
		
		/*labels de la agenda
		
		/*
		$xtpl->assign("ayuda_label", $this->localize( "ayuda" ) );
		$xtpl->assign("ayuda_agenda_msg", $this->localize( "ayuda.agenda.titulo" ) );
		$xtpl->assign("linkAyudaAgenda", LinkBuilder::getPageUrl( "AyudaTurnos") );
		
		
		$xtpl->assign("fecha_label", $this->localize( "agenda.fecha" ) );
		$xtpl->assign("fecha", TurnosUtils::formatDateToView( $this->getFecha(), "d/m/Y ") );
		$xtpl->assign("profesional_oid",  $this->getProfesional()->getOid() );
		
		$xtpl->assign("hora_label",  $this->localize( "turno.hora" ) );
		$xtpl->assign("cliente_label",  $this->localize( "turno.cliente" ) );
		$xtpl->assign("os_label",  $this->localize( "turno.obraSocial" ) );
		$xtpl->assign("estado_label",  $this->localize( "turno.estado" ) );
		$xtpl->assign("importe_label",  $this->localize( "turno.importe" ) );

		$xtpl->assign("agregar_label", $this->localize( "turno.agregar" ) );
		$xtpl->assign("enSala_label", $this->localize( "turno.enSala" ) );
		$xtpl->assign("asignado_label", $this->localize( "turno.asignado" ) );
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
		$xtpl->assign("linkAsignado",  LinkBuilder::getActionAjaxUrl( "TurnoAsignado") );
		
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
	*/
	

	
	
	
	/*********************************************************************/
	
	/**
	 * armamos la matriz de turnos
	 * 
	 *   array [ hora ] [ dia ] [ turno ] 
	 *   
	 * Enter description here ...
	 * @param unknown_type $turnosSemana
	 *
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
	 *
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
	 *
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
	 *
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
	 *
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

	
	protected function parseAgendaSemanal(XTemplate $xtpl){

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
					
					$xtpl->assign("turno_css", "turno_vacio_sobreturno");
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
		
		
		*
		
	}
	*/
	

	
}
?>