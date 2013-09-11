<?php
namespace Turnos\UI\components\ayuda;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\components\RastyComponent;
use Rasty\security\RastySecurityContext;
use Rasty\utils\LinkBuilder;

use Rasty\Menu\menu\model\MenuOption;

use Turnos\Core\model\EstadoTurno;


/**
 * componente que describe los estados de un turno
 * 
 * @author bernardo
 * @since 03/09/2013
 */
class AyudaEstadoTurno extends RastyComponent{

	
	public function __construct(){
		
	}
	
	public function getType(){
		
		return "AyudaEstadoTurno";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("ayuda_titulo", $this->localize( "ayuda.turno.estados.titulo" ));
		
		$xtpl->assign("enSala_label", $this->localize(EstadoTurno::getLabel(EstadoTurno::EnSala )) );
		$xtpl->assign("turno_enSala_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::EnSala));
		
		$xtpl->assign("asignado_label", $this->localize(EstadoTurno::getLabel(EstadoTurno::Asignado) )); 
		$xtpl->assign("turno_asignado_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::Asignado));
		
		$xtpl->assign("enCurso_label", $this->localize(EstadoTurno::getLabel(EstadoTurno::EnCurso )) );
		$xtpl->assign("turno_enCurso_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::EnCurso));
		
		$xtpl->assign("atendido_label", $this->localize(EstadoTurno::getLabel(EstadoTurno::Atendido )) );
		$xtpl->assign("turno_atendido_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::Atendido));

		
		$xtpl->assign("linkAyudaAgregarTurno", LinkBuilder::getComponentUrl( "AyudaAgregarTurno" ) );
		$xtpl->assign("ayuda_agregar_turno_titulo", $this->localize( "ayuda.agenda.agregarTurno.titulo" ) );
		
		$xtpl->assign("linkAyudaColoresAgenda", LinkBuilder::getComponentUrl( "AyudaColoresAgenda" ) );
		$xtpl->assign("ayuda_agenda_colores_titulo", $this->localize( "ayuda.agenda.colores.titulo" ) );
		
		$xtpl->assign("linkAyudaIconosAgenda", LinkBuilder::getComponentUrl( "AyudaIconosAgenda" ) );
		$xtpl->assign("ayuda_agenda_iconos_titulo", $this->localize( "ayuda.agenda.iconos.titulo" ) );
		
		//$xtpl->assign("linkAyudaIconosAgenda", LinkBuilder::getComponentUrl( "AyudaIconosAgenda" ) );
		//$xtpl->assign("ayuda_agenda_iconos_titulo", $this->localize( "ayuda.agenda.iconos.titulo" ) );
		
		$xtpl->assign("linkAyudaEstadoTurno", LinkBuilder::getComponentUrl( "AyudaEstadoTurno" ) );
		
		
	}

}
?>