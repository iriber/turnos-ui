<?php

namespace Turnos\UI\components\agenda\helper;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;
use Rasty\utils\LinkBuilder;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Turno;
use Turnos\Core\model\Prioridad;


/**
 * Helper para la agenda de turnos
 * 
 * @author bernardo
 * @since 09/09/2013
 */
class AgendaDiariaHelper{
		
	
	/**
	 * Se retorna la grilla de horarios de acuerdo a los horarios definidos para el profesional
	 * en la fecha indicada.
	 * armamos un array vacío donde los índices son los horarios.
	 * @param Profesional $profesional
	 * @param \Datetime $fecha
	 */
	public static function getGrillaHorarios( Profesional $profesional, \Datetime $fecha ){
		
		//obtenemos los horarios en que atiende.
		$horarios = UIServiceFactory::getUIHorarioService()->getHorariosDelDia( $fecha, $profesional);
		
		//creamos la agenda con los horarios del profesional.
		$grillaHorarios = array();
		foreach ($horarios as $horario) {

			$desde = TurnosUtils::formatTimeToView( $horario->getHoraDesde() );
			$hasta = TurnosUtils::formatTimeToView( $horario->getHoraHasta() );
			$duracion = $horario->getDuracionTurno();
			
			while( $desde <= $hasta){
			
				$turnoVacio = null;
				$grillaHorarios[$desde] = $turnoVacio;
				
				$desde = TurnosUtils::addMinutes($desde, "H:i", $duracion);
				
			}
			
		}
		return $grillaHorarios;
	}	
	
	/**
	 * agregamos a la grilla de horarios los turnos que fueron otorgados.
	 * @param array $grillaHorarios
	 * @param Profesional $profesional
	 * @param \Datetime $fecha
	 */
	public static function fillTurnosOtorgados( $grillaHorarios, Profesional $profesional, \Datetime $fecha ){
		
		//buscamos los turnos dado el profesional y la fecha.
		$turnoService = UIServiceFactory::getUITurnoService();
		$turnos = $turnoService->getTurnosDelDia( $fecha, $profesional);
		
		//rellenamos la agenda los turnos asignados.
		foreach ($turnos as $turno) {

			$grillaHorarios[TurnosUtils::formatTimeToView( $turno->getHora() )] = $turno;
		}
		
		return $grillaHorarios;
	}
	
	/**
	 * busca una ausencia dentro de las ausencias del profesional
	 * @param string $horaDesde es un string H:i
	 * @param array $ausencias
	 */
	public static function getAusencia($horaDesde, $ausencias){
		
		//formateamos la hora.
		$horaDatetime = new \Datetime();
		$horaDatetime->setTime(date("H", strtotime($horaDesde)), date("i", strtotime($horaDesde)), 0);

		$ausenciaRes = null;
		foreach ($ausencias as $ausencia) {
			
			if( !$ausencia->horaDisponible($horaDatetime) ){
				$ausenciaRes = $ausencia;
				break;
			}
		}
		
		return  $ausenciaRes;		
					
	}
	
		
	public static function parseAgenda(XTemplate $xtpl, Profesional $profesional, \Datetime $fecha){

		//obtenemos las ausencias notificadas por el profesional.
		//$ausencias = UIServiceFactory::getUIAusenciaService()->getAusenciasVigentesEnRango($fecha, $fecha, $profesional);
		$ausencias = UIServiceFactory::getUIAusenciaService()->getAusenciasDelDia( $fecha, $profesional);
		
		//obtenemos la grilla de horarios del profesional
		$grillaHorarios = self::getGrillaHorarios($profesional, $fecha);
		
		//rellenamos con los turnos que ya fueron otorgados.
		$grillaHorarios = self::fillTurnosOtorgados($grillaHorarios, $profesional, $fecha);
		
		
		//parseamos textos.
		self::parseLabels( $xtpl,$profesional, $fecha );
		
		
		//mostramos los turnos (asignados + vacíos)
		//los ordenamos por hora.
		ksort($grillaHorarios);		
		
		//vamos guardando la última ausencia para evitar 
		//escribir el texto si abarca horarios consecutivos.					
		$ausenciaAnterior = null;

		$index=0;
		$totalTurnos = count($grillaHorarios);
		$turnoAnteriorHoraDesde = null;
		$turnoAnteriorHoraHasta = null;
		
		$indexMostrados = 0;
		
		foreach ($grillaHorarios as $horaDesde => $turno) {
			
			$index++;
			
			//chequeamos si no es abarcado por el turno anterior.
			//en este caso, no dibujamos las horas para asignar turnos pero si hay un turno en el medio, lo mostramos igual
			//por si hay un sobreturno.
			$correspondeTurnoAnterior = false;
			if( TurnosUtils::horaSuperpuesta( $horaDesde, $turnoAnteriorHoraDesde, $turnoAnteriorHoraHasta  ) )
				$correspondeTurnoAnterior = true;;
			
			//recuperamos la ausencia para el horario si es que existe.
			$ausencia = self::getAusencia($horaDesde, $ausencias);
			
			//el turno estará disponible si, no hay ausencia o no fue otorgado.
			$turnoDisponible = ($ausencia==null)|| ($turno != null);
			
			//el turno se mostrará como no disponible en caso de tener una ausencia para la fecha y horario
			//o bien puede ser que se haya dado un sobreturno??.
			$templateBlockTurno = ($turnoDisponible)?"turno_disponible":"turno_no_disponible";
			
			
			$xtpl->assign("hora", $horaDesde );
			$xtpl->assign("horaEncode", urlencode($horaDesde) );
			
			
			//parseamos el turno.
			if( $turno == null ){
				
				//turno vacío, link para dar de alta.
				
				//TODO chequeamos si es un horario no disponible.
				if( $turnoDisponible && !$correspondeTurnoAnterior){

					$params = array();
					$params["hora"] = $horaDesde; 
					$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "TurnoAgregar", $params) );
					$xtpl->assign("linkSeleccionarLabel",  self::localize("turno.agregar") );
					
					$xtpl->assign("cliente", "");
					$xtpl->assign("estado", "");
					$xtpl->assign("obra_social", ""); //$turno->getObraSocial()->__toString());
					$xtpl->assign("importe", "" );
					$xtpl->assign("turno_css", "turno_libre" );
					$xtpl->parse("main.turno.$templateBlockTurno.agregar");

					$xtpl->parse("main.turno.$templateBlockTurno.libre");
				}elseif( !$correspondeTurnoAnterior){
					
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
					$params["profesionalOid"] = $profesional->getOid(); 
					$xtpl->assign("linkSeleccionarTurno",  LinkBuilder::getPageUrl( "AusenciaAgregar", $params) );
					$xtpl->assign("linkSeleccionarLabel",  self::localize("ausencia.ver") );
						
				}
				
				
				//actualizamos la fecha no disponible anterior para el siguiente turno
				$ausenciaAnterior = $ausencia;
				
			}else{
				
				//turno ocupado, mostramos el paciente y las distintas opciones
				
				//duración del turno
				$duracion = $turno->getDuracion();
				if($duracion>15){
					$xtpl->assign("duracion", " ($duracion min)" );
					$xtpl->parse("main.turno.$templateBlockTurno.duracion");
				}
				
				//como la duración ya no es fija, puede abarcar varios turnos fijos, vamos
				//a guardar lo que abarca el turno => horasDesde a horaHasta. Con esto, antes de
				//mostrar el próximo turno, chequeamos si está incluido por este mismo. 
				$turnoAnteriorHoraDesde = $horaDesde;
				$turnoAnteriorHoraHasta = TurnosUtils::addMinutes($horaDesde, "H:i", $duracion);
				
				//mostramos la prioridad.
				if( $turno->getPrioridad() > 1 ){
					$xtpl->assign("prioridad_css", TurnosUtils::getPrioridadTurnoCss($turno->getPrioridad()));
					$xtpl->assign("prioridad", TurnosUtils::localize( Prioridad::getLabel($turno->getPrioridad()) ) );
					$xtpl->parse("main.turno.$templateBlockTurno.ocupado.prioridad");
				}
				
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
				$xtpl->assign("linkSeleccionarLabel",  self::localize("turno.editar") );
				
				$xtpl->assign("linkHistoriaClinica",  LinkBuilder::getPageUrl( "HistoriaClinica", array("clienteOid"=> $turno->getCliente()->getOid())) );
				
				$xtpl->assign("estado", self::localize(EstadoTurno::getLabel($turno->getEstado())) );
				
				$os = $turno->getObraSocial();
				$os = ($os!=null)? $os->getNombre() : "";
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

			
			//si na hoy turno dado y el horario se superpone con el turno anterior, no mostramos el horario
			if( $correspondeTurnoAnterior && $turno == null){
					
				//TODO
				
			}else{

				$indexMostrados++;
				
				$xtpl->assign("odd_css", (($indexMostrados % 2) == 0)?"odd":"");
				$xtpl->assign("first_css", ($indexMostrados == 1)?"first":"");
				$xtpl->assign("last_css", ($indexMostrados == $totalTurnos)?"last":"");
				
				$xtpl->assign("prioridad_css", "");
				
				
				$xtpl->parse("main.turno.$templateBlockTurno");
				$xtpl->parse("main.turno");	
			}
			
		}
		
		
		//si no hay nada, no atiende. mostramos el mensaje
		if($totalTurnos==0){
			$xtpl->assign("no_atiende_msg",  self::localize("agenda.no_atiende")  );
			$xtpl->parse("main.no_atiende");
		}
		
	}
	
	private static function parseLabels(XTemplate $xtpl, Profesional $profesional, \Datetime $fecha){
		
		/*labels de la agenda*/

		
		$xtpl->assign("fecha_label", self::localize( "agenda.fecha" ) );
		$xtpl->assign("ver_semana_label", self::localize( "agenda.ver.semana" ) );
		$xtpl->assign("fecha", TurnosUtils::formatDateToView( $fecha, "d/m/Y ") );
		$xtpl->assign("profesional_oid",  $profesional->getOid() );
		
		$xtpl->parse("main.agendaDiariaTitle");
		
		$xtpl->assign("hora_label",  self::localize( "turno.hora" ) );
		$xtpl->assign("cliente_label",  self::localize( "turno.cliente" ) );
		$xtpl->assign("os_label",  self::localize( "turno.obraSocial" ) );
		$xtpl->assign("estado_label",  self::localize( "turno.estado" ) );
		$xtpl->assign("importe_label",  self::localize( "turno.importe" ) );

		$xtpl->assign("agregar_label", self::localize( "turno.agregar" ) );
		$xtpl->assign("enSala_label", self::localize( "turno.enSala" ) );
		$xtpl->assign("asignado_label", self::localize( "turno.asignado" ) );
		$xtpl->assign("editar_label", self::localize( "turno.editar" ) );
		$xtpl->assign("borrar_label", self::localize( "turno.borrar" ) );
		$xtpl->assign("iniciar_label", self::localize( "turno.iniciar" ) );
		$xtpl->assign("finalizar_label", self::localize( "turno.finalizar" ) );
		$xtpl->assign("agregar_sobreturno_label", self::localize( "turno.agregar_sobreturno" ) );		
		$xtpl->assign("historiaClinica_label", self::localize( "turno.historiaClinica" ) );		
		
		$xtpl->assign("linkModificar",  LinkBuilder::getPageUrl( "TurnoModificar") );
		$xtpl->assign("linkBorrar",  LinkBuilder::getActionAjaxUrl( "BorrarTurno") );
		$xtpl->assign("linkAgregar",  LinkBuilder::getPageUrl( "TurnoAgregar") );
		$xtpl->assign("linkIniciar",  LinkBuilder::getActionAjaxUrl( "IniciarTurno") );
		$xtpl->assign("linkFinalizar",  LinkBuilder::getActionAjaxUrl( "FinalizarTurno") );
		$xtpl->assign("linkEnSala",  LinkBuilder::getActionAjaxUrl( "TurnoEnSala") );
		$xtpl->assign("linkAsignado",  LinkBuilder::getActionAjaxUrl( "TurnoAsignado") );
		
		
	}
	
	private static function localize($keyMessage){
		return Locale::localize( $keyMessage );
	}
	
}
?>