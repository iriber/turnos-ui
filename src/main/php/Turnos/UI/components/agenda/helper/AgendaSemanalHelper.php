<?php

namespace Turnos\UI\components\agenda\helper;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;
use Rasty\utils\LinkBuilder;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\utils\Logger;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Turno;
use Turnos\Core\model\DiaSemana;

/**
 * Helper para la agenda de turnos semanal
 * 
 * @author bernardo
 * @since 09/09/2013
 */
class AgendaSemanalHelper{

	/**
	 * se obtiene el listado de turnos para la fecha
	 * será una matriz donde la clave es la hora del turno.
	 * @param array $turnosSemana
	 * @param \Datetime $fecha
	 * return array[hora]=turno
	 */
	private static function getTurnosDia($turnosSemana,\Datetime $fecha){
		
		$turnos = array();
		
		foreach ($turnosSemana as $turno) {
			
		   	if( $turno->getFecha()->format("Ymd") == $fecha->format("Ymd") )
				
			    $turnos[$turno->getHora()->format("H:i")] = $turno;
			    	
			
		}
		
		return  $turnos;
	}

	/**
	 * se obtiene el una matriz donde la clave es la hora y el
	 * valor será TRUE o FALSE dependiendo si el profesional atiende
	 * en dicha horario.
	 * @param array $horariosDia
	 * @param \Datetime $fecha
	 * return array[hora]=TRUE|FALSE
	 */
	private static function getMatrizHorariosDia($horarios,\Datetime $fecha){
		
		$horas = array();
		$dia = DiaSemana::getDia($fecha);
		
		foreach ($horarios as $horario) {

			if( $horario->getDia() == $dia ){
				
				$horaDesde = $horario->getHoraDesde();
				$horaHasta = $horario->getHoraHasta();
				$duracion = $horario->getDuracionTurno();
				$hora = new \DateTime();
				$hora->setTimestamp( $horaDesde->getTimestamp() );
				//vamos rellenando los horarios.
				while ($hora<=$horaHasta) {

					$horas[ $hora->format("H:i") ] = true;
			
					$hora->modify("$duracion minutes");
				}
			}
				
			
		}
		
		return  $horas;
	}
	
	/**
	 * se obtiene el listado de turnos para la fecha
	 * será una matriz donde la clave es la hora del turno.
	 * @param array $turnosSemana
	 * @param \Datetime $fecha
	 * return array[hora]=turno
	 */
	private static function getMatrizTurnosDia($turnosSemana,\Datetime $fecha){
		
		$turnos = array();
		
		foreach ($turnosSemana as $turno) {
			
		   	if( $turno->getFecha()->format("Ymd") == $fecha->format("Ymd") )
				
			    $turnos[$turno->getHora()->format("H:i")] = $turno;
			    	
			
		}
		
		return  $turnos;
	}
	
	/**
	 * se obtiene el listado de ausencias para la fecha
	 * será una matriz donde la clave es la hora de la ausencia.
	 * @param array $ausencias
	 * @param \Datetime $fecha
	 * return array[hora]=ausencia
	 */
	private static function getMatrizAusenciaDia($rangosMatriz, $ausencias,\Datetime $fecha){
		
		$matriz = array();
		
		foreach ($ausencias as $ausencia) {
			
			//por cada ausencia tenemos que indicar si está vigente para un horario dado.
			$horaDesde = $rangosMatriz[0];
			$horaHasta = $rangosMatriz[1];
			$duracionMenor = $rangosMatriz[2];
		
			$hora = new \DateTime();
			$hora->setTimestamp( $horaDesde->getTimestamp() );
		
			//vamos rellenando la matriz con los turnos.
			while ($hora<=$horaHasta) {

				if( $ausencia->isVigente($fecha, $hora) ){
				
					$matriz[$hora->format("H:i")] = $ausencia;
				
				}
				
				$hora->modify("$duracionMenor minutes");
			}
			
			
		}
		
		return  $matriz;
	}
	
	/**
	 * armamos la matriz de turnos
	 * 
	 *   array [ dia ] [ horarios ] 
	 *   			   [ turnos ]
	 *   			   [ ausencia ]
	 *   
	 * Enter description here ...
	 * @param unknown_type $turnosSemana
	 */
	private static function GrillaHorarios($rangosMatriz, $ausencias, $horarios, $turnosSemana,\Datetime $fechaDesde, \Datetime $fechaHasta, Profesional $profesional){
		
		$matriz = array();
		
		//la matriz va quedando matriz[dia][horarios] = horarios del día.
		$fecha = new \Datetime();
		$fecha->setTimestamp($fechaDesde->getTimestamp() );
		
		
		while ( $fecha <= $fechaHasta ){
			
			$matriz[$fecha->format("Y-m-d")] = array();
			
			//vamos rellenando la matriz con los horarios en que atiende para cada fecha.
			$matriz[$fecha->format("Y-m-d")]["horarios"] = self::getMatrizHorariosDia($horarios, $fecha);
			
			//ahora con los turnos.
			$matriz[$fecha->format("Y-m-d")]["turnos"] = self::getMatrizTurnosDia( $turnosSemana, $fecha ); 
			
			//por lo último, si tiene ausencia, la agregamos.
			$matriz[$fecha->format("Y-m-d")]["ausencias"] = self::getMatrizAusenciaDia( $rangosMatriz, $ausencias, $fecha );
			
			$fecha->add(new \DateInterval('P1D'));
		}

		return $matriz;
	}
	
	private static function isGrillaHoraVacia( $grillaHorarios, $horaKey ){
		
		$vacia = true;
		foreach ($grillaHorarios as $matriz) {
				
			$horarios = $matriz["horarios"];
			$turnos = $matriz["turnos"];
			if ( array_key_exists($horaKey, $turnos)   ||  array_key_exists($horaKey, $horarios) ) 
				return false;
		}
		
		return $vacia;
	}

	public static function parseAgenda2(XTemplate $xtpl, Profesional $profesional, \Datetime $fechaSeleccionada){

		
		$fechaDesde = TurnosUtils::getFirstDayOfWeek($fechaSeleccionada);
		
		$fechaHasta = TurnosUtils::getLastDayOfWeek($fechaSeleccionada);
		
		//mostramos el encabezado;
		self::parseDias($xtpl, $fechaDesde, $fechaHasta, $fechaSeleccionada);
		
		$xtpl->parse("main.agendaSemanalTitle");
		
		//obtenemos los turnos de la semana.
		$turnosSemana = UIServiceFactory::getUITurnoService()->getTurnosSemana($fechaDesde, $fechaHasta, $profesional);

		//obtenemos las ausencias notificadas por el profesional.
		$ausencias = UIServiceFactory::getUIAusenciaService()->getAusenciasVigentesEnRango($fechaDesde, $fechaHasta, $profesional);
		
		//obtenemos los horarios de atención del profesional.
		$horarios = UIServiceFactory::getUIHorarioService()->getHorariosDelProfesional( $profesional);

		//obtenemos los rangos de la matriz de horarios (hora desde-hasta, y la menor duración)
		$rangosMatriz = self::getRangosMatriz( $horarios, $turnosSemana );
		
		//armamos la grilla de horarios.
		$grillaHorarios = self::GrillaHorarios($rangosMatriz, $ausencias, $horarios, $turnosSemana, $fechaDesde, $fechaHasta, $profesional);

		$xtpl->assign("linkBorrar",   LinkBuilder::getActionAjaxUrl( "BorrarTurno") );
		$xtpl->assign("borrar_label", self::localize( "turno.borrar" ) );
		$xtpl->assign("enSala_label", self::localize( "turno.enSala" ) );
		$xtpl->assign("asignado_label", self::localize( "turno.asignado" ) );
		$xtpl->assign("linkEnSala",  LinkBuilder::getActionAjaxUrl( "TurnoEnSala") );
		$xtpl->assign("linkAsignado",  LinkBuilder::getActionAjaxUrl( "TurnoAsignado") );
		$xtpl->assign("linkHistoriaClinicaLabel", self::localize( "turno.historiaClinica" ) );		
		
		$hora = new \DateTime();
		//$hora->setTimestamp( $horaDesde->getTimestamp() );
		$hora->setTime(0, 0);
		$horaHasta = new \DateTime();
		$horaHasta->setTime(23, 59);
		$duracionMenor = 1;
		
		//vamos rellenando la matriz con los turnos.
		while ($hora<=$horaHasta) {

			$horaKey = $hora->format("H:i");

			//si para la hora indicada no hay nada (ni horarios, ni turnos) directamente no la mostramos.
			if(  !self::isGrillaHoraVacia($grillaHorarios, $horaKey)  )
				
					self::parseHora($xtpl, $grillaHorarios, $horaKey, $profesional, $rangosMatriz, $fechaSeleccionada );
			
			$hora->modify("$duracionMenor minutes");				
				
		}
		
		
	}
	
	private static function parseHora(XTemplate $xtpl, $grillaHorarios, $horaKey, $profesional, $rangosMatriz, \Datetime $fechaSeleccionada){
		
		$xtpl->assign("hora", $horaKey);
		
		//recorremos para cada fecha
		$fechaAnterior = null;

		$horasMostradas = self::getHorasMostradas($grillaHorarios);
		
		foreach ($grillaHorarios as $fecha => $matriz) {
			
			//fecha es un string Y-m-d
			$fechaArray = explode("-", $fecha);
			$fechaMostrar = TurnosUtils::getNewDate($fechaArray[2], $fechaArray[1], $fechaArray[0]);
			$fechaActual = TurnosUtils::getNewDate($fechaArray[2], $fechaArray[1], $fechaArray[0]);

			if( TurnosUtils::fechasIguales($fechaSeleccionada, $fechaMostrar)){
				
				//chequeo si es la última hora mostrada.(para dar formato a la grilla)
				$ultimaHora = self::esUltimaHoraGrilla($horaKey, $horasMostradas);
		
				if($ultimaHora)
					$xtpl->assign("turno_dia_css", "agenda_semanal_turno_fechaSeleccionada_last");
				else	
					$xtpl->assign("turno_dia_css", "agenda_semanal_turno_fechaSeleccionada");
				
			}else{
				$xtpl->assign("turno_dia_css", "");
			}
			
			$fechaMostrar = TurnosUtils::formatDateToView($fechaMostrar, "D j-M");
			
			$horarios = $matriz["horarios"];
			$ausencias = $matriz["ausencias"];
			$turnos = $matriz["turnos"];
			
			//vemos si el horario+fecha corresponde al turno anterior.
			//tenemos que comparar con el turno anterior de la fecha dada
			$correspondeTurnoAnterior = self::correspondeTurnoAnterior( $horaKey, $fecha, $grillaHorarios );
			
		
			
			//si hay turno, lo muestro
			if( array_key_exists($horaKey, $turnos)){
				
				//hay un turno asignado
				$turno = $turnos[$horaKey];
				
				$cantidadFilas = self::getCantidadFilas( $fecha, $grillaHorarios, $turno, $rangosMatriz, $horasMostradas );
				
				$xtpl->assign("turno_filas", $cantidadFilas);
				
				
				$xtpl->assign("turno_css", TurnosUtils::getEstadoTurnoCss($turno->getEstado()));
					
				$xtpl->assign("turno_oid", $turno->getOid() );
				
				$cliente = $turno->getCliente();
				if(!empty($cliente) && $cliente->getOid()>0){
					$xtpl->assign("cliente",  $turno->getCliente()->__toString() );
					$xtpl->assign("cliente_oid",  $turno->getCliente()->getOid());
					$xtpl->assign("linkHistoriaClinica",  LinkBuilder::getPageUrl( "HistoriaClinica", array("clienteOid"=> $turno->getCliente()->getOid())) );
					$xtpl->parse("main.hora.dia.turno.historia_clinica");	
					
				}else{
					$xtpl->assign("cliente", $turno->getNombre() );
				}	
				$xtpl->assign("estado", self::localize(EstadoTurno::getLabel($turno->getEstado())) );
				
				$os = $turno->getObraSocial();
				$os = ($os!=null)? $os->getNombre() : "-";
				$xtpl->assign("obra_social", $os );
				$xtpl->assign("nroObraSocial", $turno->getNroObraSocial() );
				
				$importe = $turno->getImporte();
				$xtpl->assign("importe", TurnosUtils::formatMontoToView($importe) );
				
				if( $turno->getEstado() == EstadoTurno::EnSala ){
		
					$xtpl->parse("main.hora.dia.turno.ensala");
					
				}elseif( $turno->getEstado() == EstadoTurno::Asignado ){
					$xtpl->parse("main.hora.dia.turno.asignado");
				}else 
					$xtpl->parse("main.hora.dia.turno.otro_estado");
		

				$xtpl->assign("linkSeleccionarTurno",   LinkBuilder::getPageUrl( "TurnoModificar" , array("oid"=> $turno->getOid())) );
				$xtpl->assign("linkSeleccionarLabel",  self::localize("turno.editar") );
				
				
				$xtpl->parse("main.hora.dia.turno");
				
			}elseif( $correspondeTurnoAnterior){
				
				$xtpl->parse("main.hora.dia.turno_extendido");
				
			}elseif( array_key_exists($horaKey, $horarios)){
				
				$xtpl->assign("turno_filas", 1);
				
				//atiende.
				
				if( array_key_exists($horaKey, $ausencias)){
					//hay ausencia.
					
					$ausencia = $ausencias[$horaKey];
					$xtpl->assign("turno_css", "turno_ausencia");
					$params = array();
					$params["profesionalOid"] = $profesional->getOid(); 
					$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "AusenciaAgregar", $params) );
					$xtpl->assign("linkSeleccionarLabel",  TurnosUtils::getMensajeAusencia($ausencia) );
					$xtpl->parse("main.hora.dia.turno_ausencia");
					
				}else{
					
					//TODO chequear si corresponde a otro turno (mayores a la duración estándar).
					
					//agregar turno.
					$xtpl->assign("turno_css", "turno_vacio_disponible");
					
					$params = array( $horaKey, $fechaMostrar  );
					$xtpl->assign("mensajeOculto", TurnosUtils::formatMessage( self::localize("turno.agregar.fechaHora"), $params ));
				
					$params = array();
					$params["hora"] = $horaKey;
					$params["fecha"] = $fecha; 
					$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "TurnoAgregar", $params) );
					$xtpl->assign("linkSeleccionarLabel",  self::localize("turno.agregar") );
					$xtpl->assign("fecha", TurnosUtils::formatDateToView( $fechaActual, "d/m/Y ") );
					$xtpl->parse("main.hora.dia.turno_vacio_disponible");
				
				}
				
			}else{
				
				$xtpl->assign("turno_filas", 1);
				
				//vacío, no mostrar nada. posible sobreturno?			
				$xtpl->assign("turno_css", "turno_vacio_sobreturno");
				$params = array( $horaKey, $fechaMostrar );
				$xtpl->assign("mensajeOculto", TurnosUtils::formatMessage( self::localize("turno.agregar.fechaHora"), $params ));
				
				$params = array();
				$params["hora"] = $horaKey;
				$params["fecha"] = $fecha; 
				$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "SobreturnoAgregar", $params) );
				$xtpl->assign("linkSeleccionarLabel",  self::localize("turno.agregar_sobreturno") );
				$xtpl->parse("main.hora.dia.turno_vacio_sobreturno");	
				
			}
			
			$xtpl->parse("main.hora.dia");
			
			$fechaArray = explode("-", $fecha);
			$fechaAnterior = TurnosUtils::getNewDate($fechaArray[2], $fechaArray[1], $fechaArray[0]);
		}			
					
		
		$xtpl->parse("main.hora");
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/**
	 * armamos la matriz de turnos
	 * 
	 *   array [ hora ] [ dia ] [ turno ] 
	 *   
	 * Enter description here ...
	 * @param unknown_type $turnosSemana
	 */
	private static function MatrizTurnos($turnosSemana,\Datetime $fechaDesde, \Datetime $fechaHasta, Profesional $profesional){
		
		$matriz = array();
		
		
		//obtenemos los horarios en que atiende para cada fecha.
		$fecha = new \Datetime();
		$fecha->setTimestamp($fechaDesde->getTimestamp() );
		
		$horarios = array();
		while ( $fecha <= $fechaHasta ){
			$horarios[$fecha->format("Y-m-d")] = UIServiceFactory::getUIHorarioService()->getHorariosDelDia( $fecha,$profesional);
			$fecha->add(new \DateInterval('P1D'));
		}

		
		//obtenemos los rangos de la matriz (hora desde-hasta, y la menor duración)
		$rangosMatriz = self::getRangosMatriz( $horarios, $turnosSemana );
		$horaDesde = $rangosMatriz[0];
		$horaHasta = $rangosMatriz[1];
		$duracionMenor = $rangosMatriz[2];
		
		
		$hora = new \DateTime();
		$hora->setTimestamp( $horaDesde->getTimestamp() );
		
		//vamos rellenando la matriz con los horarios y los turnos.
		while ($hora<=$horaHasta) {

			$matriz[$hora->format("H:i")] = self::getTurnosHora($hora, $turnosSemana,$fechaDesde, $fechaHasta); //array con los turnos de cada día en ese horario.
			
			$hora->modify("$duracionMenor minutes");
		}
		
		//debemos agregarle los sobreturnos.
		$matriz = self::agregarSobreturnos($matriz, $turnosSemana , $fechaDesde, $fechaHasta);		
		
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
	private static function agregarSobreturnos($matriz, $turnosSemana, \Datetime $fechaDesde, \Datetime $fechaHasta ){
		
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
		
		$horariosSobreturno = array();
		
		foreach ($sobreturnos as $horaTurno => $turnos) {
			
			$horaMin = explode(":", $horaTurno);
			$hora = new \Datetime();
			$hora->setTime( $horaMin[0], $horaMin[1]);
			$matriz[$horaTurno] = self::getTurnosHora($hora, $turnos,$fechaDesde, $fechaHasta, "vacio"); //array con los turnos de cada día en ese horario.
			
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
	private static function getTurnosHora($hora, $turnosSemana,\Datetime $fechaDesde, \Datetime $fechaHasta, $vacio=null){
		
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
	private static function getRangosMatriz($horarios){
		
		$horaMenor = new \DateTime();
		$horaMenor->setTime(23, 59);
		
		$horaMayor = new \DateTime();
		$horaMayor->setTime(0, 0);
		
		$duracionMenor = 60;
		
		foreach ($horarios as $horario) {
				
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
		
		return array ( $horaMenor, $horaMayor, $duracionMenor );
	}
	
	/**
	 * parseamos el encabezado de la semana, los días.
	 * @param XTemplate $xtpl
	 * @param \Datetime $fechaDesde
	 * @param \Datetime $fechaHasta
	 */	
	private static function parseDias(XTemplate $xtpl, \Datetime $fechaDesde, \Datetime $fechaHasta, \Datetime $fechaSeleccionada){
		
		$fecha = new \Datetime();
		$fecha->setTimestamp($fechaDesde->getTimestamp() );
		
		$formatoFecha = "D j-M";
		
		while ( $fecha <= $fechaHasta ){
			
			if( TurnosUtils::fechasIguales($fecha, $fechaSeleccionada)){
				$xtpl->assign("dia_css", "agenda_semanal_turno_fechaSeleccionada_header");
			}else{
				$xtpl->assign("dia_css", "");
			}
			
			$xtpl->assign("fecha", urlencode( $fecha->format("Y-m-d") ));
			$xtpl->assign("linkSeleccionarDiaLabel",  self::localize("agenda.ver.dia") );
			$xtpl->assign("dia", TurnosUtils::formatDateToView($fecha, $formatoFecha));
			$xtpl->parse("main.th_dia");			
				
			$fecha->add(new \DateInterval('P1D'));
		}
		
		$formatoFecha = "j-M";
		$xtpl->assign("semana", RastyUtils::formatMessage(self::localize( "agenda.semana" ), array(TurnosUtils::formatDateToView($fechaDesde, $formatoFecha), TurnosUtils::formatDateToView($fechaHasta, $formatoFecha)) ) );
	}

	/*
	public static function parseAgenda(XTemplate $xtpl, Profesional $profesional, \Datetime $fechaSeleccionada){

		
		$fechaDesde = TurnosUtils::getFirstDayOfWeek($fechaSeleccionada);
		
		$fechaHasta = TurnosUtils::getLastDayOfWeek($fechaSeleccionada);
		
		//mostramos el encabezado;
		self::parseDias($xtpl, $fechaDesde, $fechaHasta, $fechaSeleccionada);
		
		$xtpl->parse("main.agendaSemanalTitle");
		
		$turnosSemana = UIServiceFactory::getUITurnoService()->getTurnosSemana($fechaDesde, $fechaHasta, $profesional);
		
		$turnosMostrar = self::MatrizTurnos($turnosSemana,$fechaDesde, $fechaHasta, $profesional);
		

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
						$xtpl->assign("mensajeOculto", TurnosUtils::formatMessage( self::localize("turno.agregar.fechaHora"), $params ));
					
						$params = array();
						$params["hora"] = $hora;
						$params["fecha"] = $fecha; 
						$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "TurnoAgregar", $params) );
						$xtpl->assign("linkSeleccionarLabel",  self::localize("turno.agregar") );
					
						$xtpl->parse("main.hora.dia.turno_vacio_disponible");
						
						
				}elseif( is_a( $turno, get_class(new Turno()))   ) {
						
						$xtpl->assign("turno_css", TurnosUtils::getEstadoTurnoCss($turno->getEstado()));
						
						
						$cliente = $turno->getCliente();
						if(!empty($cliente) && $cliente->getOid()>0){
							$xtpl->assign("cliente",  $turno->getCliente()->__toString() );
							$xtpl->assign("cliente_oid",  $turno->getCliente()->getOid());
							//$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "HistoriaClinica" , array("clienteOid"=> $turno->getCliente()->getOid())) );
							//$xtpl->assign("linkSeleccionarLabel",  self::localize("agenda.verficha") );
						}else{
							$xtpl->assign("cliente", $turno->getNombre() );
						}	
						$xtpl->assign("estado", self::localize(EstadoTurno::getLabel($turno->getEstado())) );
						
						if( $turno->getEstado() == EstadoTurno::EnSala ){
				
							$xtpl->parse("main.hora.dia.turno.ensala");
							
						}elseif( $turno->getEstado() == EstadoTurno::Asignado ){
							$xtpl->parse("main.hora.dia.turno.asignado");
						}else 
							$xtpl->parse("main.hora.dia.turno.otro_estado");
				
	
						$xtpl->assign("linkSeleccionarTurno",   LinkBuilder::getPageUrl( "TurnoModificar" , array("oid"=> $turno->getOid())) );
						$xtpl->assign("linkSeleccionarLabel",  self::localize("turno.editar") );
						
						$xtpl->parse("main.hora.dia.turno");
						
				}else{//para los espacios vacío de horarios de sobreturnos.
					
					$xtpl->assign("turno_css", "turno_vacio_sobreturno");
					$params = array( $hora, $fechaMostrar );
					$xtpl->assign("mensajeOculto", TurnosUtils::formatMessage( self::localize("turno.agregar.fechaHora"), $params ));
					
					$params = array();
					$params["hora"] = $hora;
					$params["fecha"] = $fecha; 
					$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "SobreturnoAgregar", $params) );
					$xtpl->assign("linkSeleccionarLabel",  self::localize("turno.agregar_sobreturno") );
					$xtpl->parse("main.hora.dia.turno_vacio_sobreturno");	
				}
								
								
				$xtpl->parse("main.hora.dia");
			};

			$xtpl->parse("main.hora");
			
		}			
		
	}
	*/
	
	private static function localize($keyMessage){
		return Locale::localize( $keyMessage );
	}

	
	private static function correspondeTurnoAnterior( $horaKey, $fecha, $grillaHorarios ){
		
		$turnos = $grillaHorarios[$fecha]["turnos"];	
	 
		//vemos si en la grilla hay un turno que tenga la hora incluida
		$correspondeTurnoAnterior = false;
		foreach ($turnos as $horaTurno => $turno) {
			
			if( $turno!=null ){
				$turnoHoraDesde = $turno->getHora()->format("H:i");
				$duracion = $turno->getDuracion();
				$turnoHoraHasta = TurnosUtils::addMinutes($turnoHoraDesde, "H:i", $duracion);
				if( TurnosUtils::horaSuperpuesta( $horaKey, $turnoHoraDesde, $turnoHoraHasta  ) )
					$correspondeTurnoAnterior = true;	
			}
			
			
		}
		
		return $correspondeTurnoAnterior;
		
	}
	
	private static function getCantidadFilas( $fecha, $grillaHorarios, $turno, $rangosMatriz, $horasMostradas ){
		
		//calculamos la cantidad de filas de la grilla que abarca el turno.
		$turnoHoraDesde = $turno->getHora()->format("H:i");
		$duracion = $turno->getDuracion();
		$turnoHoraHasta = TurnosUtils::addMinutes($turnoHoraDesde, "H:i", $duracion);
		
		$turnos = $grillaHorarios[$fecha]["turnos"];
		
		
		//vamos viendo los horarios de la grilla.
		$cantidadFilas = 1;
		$terminar = false;
		//while ($hora<=$horaHasta && !$terminar) {
		$index = 0;
		while( $index < count($horasMostradas) && !$terminar){

			$horaKey = $horasMostradas[$index];
			
			if ( $horaKey == $turno->getHora()->format("H:i") ){
				//nada.
				
			}elseif(TurnosUtils::horaSuperpuesta($horaKey, $turnoHoraDesde, $turnoHoraHasta) ){
				
				
				if( array_key_exists($horaKey, $turnos) ) //si hay otro turno,terminamos. 
					$terminar = true;
				else 
					$cantidadFilas++;
			}
			
			
			//$hora->modify("$duracionMenor minutes");
			$index++;
		}
		
		//Logger::log( "cantidad de filas para $horaKey dia  $fecha: $cantidadFilas");
		return $cantidadFilas;
		
	}

	private static function getHorasMostradas($grillaHorarios){

		$hora = new \DateTime();
		//$hora->setTimestamp( $horaDesde->getTimestamp() );
		$hora->setTime(0, 0);
		$horaHasta = new \DateTime();
		$horaHasta->setTime(23, 59);
		$duracionMenor = 1;
		
		$horariosGrilla = array();
		while ($hora<=$horaHasta) {

			$horaKey = $hora->format("H:i");

			//si para la hora indicada no hay nada (ni horarios, ni turnos) directamente no la mostramos.
			if(  !self::isGrillaHoraVacia($grillaHorarios, $horaKey)  )
				
					$horariosGrilla[] = $horaKey;
			
							
			$hora->modify("$duracionMenor minutes");			
		}
		ksort($horariosGrilla);
		return $horariosGrilla;
	}
	
	private static function esUltimaHoraGrilla( $hora, $horasMostradas){
		
		$ultima = $horasMostradas[count($horasMostradas)-1];
		
		return ($hora == $ultima);
		
	}

}
?>