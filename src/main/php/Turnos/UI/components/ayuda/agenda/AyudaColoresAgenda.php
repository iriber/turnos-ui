<?php
namespace Turnos\UI\components\ayuda\agenda;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\components\RastyComponent;
use Rasty\security\RastySecurityContext;

use Turnos\Core\model\EstadoTurno;

/**
 * componente que describe la ayuda para colores en la agenda
 * 
 * @author bernardo
 * @since 02/09/2013
 */
class AyudaColoresAgenda extends RastyComponent{

	
	public function __construct(){
		
	}

	
	public function getType(){
		
		return "AyudaColoresAgenda";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("ayuda_titulo", $this->localize( "ayuda.agenda.colores.titulo" ));
		
		$xtpl->assign("enSala_label",$this->localize( EstadoTurno::getLabel(EstadoTurno::EnSala )) );
		$xtpl->assign("enSala_msg", $this->localize( "ayuda.agenda.ensala" ));
		$xtpl->assign("turno_enSala_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::EnSala));
		
		$xtpl->assign("asignado_label", $this->localize(EstadoTurno::getLabel(EstadoTurno::Asignado)) ); 
		$xtpl->assign("asignado_msg", $this->localize( "ayuda.agenda.asignado" ));
		$xtpl->assign("turno_asignado_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::Asignado));
		
		$xtpl->assign("enCurso_label", $this->localize(EstadoTurno::getLabel(EstadoTurno::EnCurso )) );
		$xtpl->assign("enCurso_msg", $this->localize( "ayuda.agenda.encurso" ));
		$xtpl->assign("turno_enCurso_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::EnCurso));
		
		$xtpl->assign("atendido_label", $this->localize(EstadoTurno::getLabel(EstadoTurno::Atendido )) );
		$xtpl->assign("atendido_msg", $this->localize( "ayuda.agenda.atendido" ));
		$xtpl->assign("turno_atendido_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::Atendido));
		
		$xtpl->assign("ausencia_label", $this->localize( "turno.ausencia.label" ));
		$xtpl->assign("ausencia_msg", $this->localize( "ayuda.agenda.ausencia" ));
		$xtpl->assign("turno_ausencia_css", "turno_nodisponible");
	}

}
?>