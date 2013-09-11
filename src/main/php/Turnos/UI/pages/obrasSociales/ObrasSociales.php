<?php
namespace Turnos\UI\pages\obrasSociales;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\components\filter\model\UIObraSocialCriteria;

use Turnos\UI\components\grid\model\ObraSocialGridModel;

use Turnos\UI\service\UIObraSocialService;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\i18n\Locale;

use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

class ObrasSociales extends TurnosPage{

	
	public function __construct(){
		
	}
	
	public function getTitle(){
		return $this->localize( "obraSocial.title" );
	}

	public function getMenuGroups(){

		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "obraSocial.agregar") );
		$menuOption->setPageName("ObraSocialAgregar");
		//$menuGroup->addMenuOption( $menuOption );
		
		
		return array($menuGroup);
	}
	
	public function getType(){
		
		return "ObrasSociales";
		
	}	

	public function getModelClazz(){
		return get_class( new ObraSocialGridModel() );
	}

	public function getUicriteriaClazz(){
		return get_class( new UIObraSocialCriteria() );
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("legend_operaciones", $this->localize("grid.operaciones") );
		$xtpl->assign("legend_resultados", $this->localize("grid.resultados") );
		
		$xtpl->assign("agregar_label", $this->localize("obraSocial.agregar") );
	}

}
?>