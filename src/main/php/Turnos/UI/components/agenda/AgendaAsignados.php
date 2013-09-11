<?php

namespace Turnos\UI\components\agenda;

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
 * Agenda de pacientes ssignados en el día del profesional logueado.
 * 
 * @author bernardo
 * @since 30/08/2013
 */
class AgendaAsignados extends AgendaEnSala{
		
	
	public function getType(){
		
		return "AgendaAsignados";
		
	}

	public function __construct(){
		
		parent::__construct();
		
		
	}
	protected function getTurnos(){
		return UIServiceFactory::getUITurnoService()->getTurnosDelDiaEstados( $this->getFecha(), $this->getProfesional(), array( EstadoTurno::Asignado));
	}

}
?>