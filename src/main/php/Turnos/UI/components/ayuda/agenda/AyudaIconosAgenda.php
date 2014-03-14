<?php
namespace Turnos\UI\components\ayuda\agenda;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\components\RastyComponent;
use Rasty\security\RastySecurityContext;

use Turnos\Core\model\EstadoTurno;

/**
 * componente que describe la ayuda para íconos en la agenda
 * 
 * @author bernardo
 * @since 02/09/2013
 */
class AyudaIconosAgenda extends RastyComponent{

	
	public function __construct(){
		
	}

	
	public function getType(){
		
		return "AyudaIconosAgenda";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("ayuda_titulo", $this->localize( "ayuda.agenda.iconos.titulo" ));
				
		$xtpl->assign("enSala_label", $this->localize(EstadoTurno::getLabel(EstadoTurno::EnSala )) );
		$xtpl->assign("ponerEnAsignado_msg", $this->localize( "ayuda.agenda.sala-asignado.msg" ));
		$xtpl->assign("turno_enSala_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::EnSala));
		
		$xtpl->assign("asignado_label", $this->localize(EstadoTurno::getLabel(EstadoTurno::Asignado)) ); 
		$xtpl->assign("ponerEnSala_msg", $this->localize( "ayuda.agenda.asignado-sala.msg" ));
		$xtpl->assign("turno_asignado_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::Asignado));

		$xtpl->assign("borrar_label", $this->localize( "turno.borrar" ) ); 
		$xtpl->assign("borrar_msg", $this->localize( "ayuda.agenda.borrar.msg" ));
		
		$xtpl->assign("historiaClinica_label", $this->localize( "turno.historiaClinica" ) ); 
		$xtpl->assign("historiaClinica_msg", $this->localize( "ayuda.agenda.historiaClinica.msg" ));
	}

}
?>