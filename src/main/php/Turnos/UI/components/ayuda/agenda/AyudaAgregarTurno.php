<?php
namespace Turnos\UI\components\ayuda\agenda;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\components\RastyComponent;
use Rasty\security\RastySecurityContext;

use Turnos\Core\model\EstadoTurno;

/**
 * componente que describe la ayuda para dar de alta
 * un nuevo turno
 * 
 * @author bernardo
 * @since 02/09/2013
 */
class AyudaAgregarTurno extends RastyComponent{

	
	public function __construct(){
		
	}

	
	public function getType(){
		
		return "AyudaAgregarTurno";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("ayuda_titulo", $this->localize( "ayuda.agenda.agregarTurno.titulo" ));
				
	}

}
?>