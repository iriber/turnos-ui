<?php
namespace Turnos\UI\components\ayuda\agenda;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\components\RastyComponent;
use Rasty\security\RastySecurityContext;
use Rasty\utils\LinkBuilder;

use Rasty\Menu\menu\model\MenuOption;

use Turnos\Core\model\EstadoTurno;


/**
 * componente que describe la ayuda para dar de alta
 * un sobre turno
 * 
 * @author bernardo
 * @since 02/09/2013
 */
class AyudaAgregarSobreturno extends RastyComponent{

	
	public function __construct(){
		
	}

	
	public function getType(){
		
		return "AyudaAgregarSobreturno";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("ayuda_titulo", $this->localize( "ayuda.agenda.agregarSobreturno.titulo" ));
		
		$xtpl->assign("opcionesPagina", $this->localize( "menu.page" ));
		
		$xtpl->assign("agregarSobreturno_img", $this->getWebPath() . "css/images/turnos_48.png" );
		$xtpl->assign("agregarSobreturno_label", $this->localize( "turno.agregar_sobreturno" ) );
		$xtpl->assign("agregarSobreturno_link", LinkBuilder::getPageUrl("SobreturnoAgregar") );
				
		$xtpl->assign("agregarSobreturno_menuOption", $this->localize( "ayuda.turno.agregarSobreturno.title" ));
		
		
	}

}
?>