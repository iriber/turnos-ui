<?php
namespace Turnos\UI\pages\nomenclador;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\components\filter\model\UINomencladorCriteria;

use Turnos\UI\components\grid\model\NomencladorGridModel;

use Turnos\UI\service\UINomencladorService;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\i18n\Locale;

use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

class Nomencladores extends TurnosPage{

	
	public function __construct(){
		
	}
	
	public function getTitle(){
		return $this->localize( "nomenclador.title" );
	}

	public function getMenuGroups(){

		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "nomenclador.agregar") );
		$menuOption->setPageName("NomencladorAgregar");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/nomenclador_48.png" );
		$menuGroup->addMenuOption( $menuOption );
		
		
		return array($menuGroup);
	}
	
	public function getType(){
		
		return "Nomencladores";
		
	}	

	public function getModelClazz(){
		return get_class( new NomencladorGridModel() );
	}

	public function getUicriteriaClazz(){
		return get_class( new UINomencladorCriteria() );
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("legend_operaciones", $this->localize("grid.operaciones") );
		$xtpl->assign("legend_resultados", $this->localize("grid.resultados") );
		
		$xtpl->assign("agregar_label", $this->localize("nomenclador.agregar") );
	}

}
?>