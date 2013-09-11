<?php
namespace Turnos\UI\components\ayuda\panel;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\components\RastyComponent;
use Rasty\security\RastySecurityContext;

use Turnos\Core\model\EstadoTurno;

/**
 * componente que describe la ayuda para íconos en el panel
 * 
 * @author bernardo
 * @since 03/09/2013
 */
class AyudaIconosPanel extends RastyComponent{

	
	public function __construct(){
		
	}

	
	public function getType(){
		
		return "AyudaIconosPanel";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("ayuda_titulo", $this->localize( "ayuda.panel.iconos.titulo" ));

		
		$xtpl->assign("pasarEnSalaEnCurso_msg", $this->localize( "ayuda.panel.sala-encurso.msg" ));
		$xtpl->assign("pasarEnCursoAtendido_msg", $this->localize( "ayuda.panel.encurso-atendido.msg" ));
		$xtpl->assign("pasarAtendidoEnSala_msg", $this->localize( "ayuda.panel.atendido-ensala.msg" ));
		$xtpl->assign("pasarAsignadoEnSala_msg", $this->localize( "ayuda.panel.asignado-ensala.msg" ));
		
		
		$xtpl->assign("turno_asignado_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::Asignado));
		$xtpl->assign("turno_enSala_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::EnSala));
		$xtpl->assign("turno_enCurso_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::EnCurso));	
		$xtpl->assign("turno_atendido_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::Atendido));

	}

}
?>