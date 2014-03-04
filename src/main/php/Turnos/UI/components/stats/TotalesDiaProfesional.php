<?php

namespace Turnos\UI\components\stats;

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
 * Totales del día del profesional logueado.
 * 
 * @author bernardo
 * @since 01/09/2013
 */
class TotalesDiaProfesional extends TotalesDia{
		
	private $profesional;
	
	
	public function getType(){
		
		return "TotalesDiaProfesional";
		
	}

	public function __construct(){
		
		parent::__construct();
		
		if(TurnosUtils::isProfesionalLogged());
			$this->setProfesional(TurnosUtils::getProfesionalLogged());
		
	}

	protected function getTurnos(){
		
		return UIServiceFactory::getUITurnoService()->getTurnosDelDia( $this->getFecha(), $this->getProfesional() );
		
	}

	public function getProfesional()
	{
	    return $this->profesional;
	}

	public function setProfesional($profesional)
	{
	    $this->profesional = $profesional;
	}

}
?>