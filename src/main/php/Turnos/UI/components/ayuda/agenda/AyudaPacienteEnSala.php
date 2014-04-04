<?php
namespace Turnos\UI\components\ayuda\agenda;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\components\RastyComponent;
use Rasty\security\RastySecurityContext;

use Turnos\Core\model\EstadoTurno;

/**
 * componente que describe la ayuda para poner un
 * paciente en sala
 * 
 * @author bernardo
 * @since 02/04/2014
 */
class AyudaPacienteEnSala extends RastyComponent{

	
	public function __construct(){
		
	}

	
	public function getType(){
		
		return "AyudaPacienteEnSala";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("ayuda_titulo", $this->localize( "ayuda.agenda.pacienteEnSala.titulo" ));
				
		$xtpl->assign("asignado_label", $this->localize(EstadoTurno::getLabel(EstadoTurno::Asignado)) ); 
		$xtpl->assign("ponerEnSala_msg", $this->localize( "ayuda.agenda.asignado-sala.msg" ));
		$xtpl->assign("turno_asignado_css", TurnosUtils::getEstadoTurnoCss(EstadoTurno::Asignado));
		
	}

}
?>