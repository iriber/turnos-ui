<?php
namespace Turnos\UI\pages\practicas;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\components\filter\model\UIPracticaCriteria;

use Turnos\UI\components\grid\model\PracticaGridModel;

use Turnos\UI\service\UIPracticaService;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\i18n\Locale;

use Turnos\Core\model\Practica;
use Turnos\Core\criteria\PracticaCriteria;
use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;


/**
 * Página para consultar los practicas.
 * 
 * @author bernardo
 * @since 01/10/2013
 * 
 * @Rasty\conf\annotations\Page (name="Practicas", 
 * 								 location="pages/practicas/Practicas.page" , 
 * 								 url="pacientes")
 */
class Practicas extends TurnosPage{

	
	public function __construct(){
		
	}
	
	public function getTitle(){
		return $this->localize( "practicas.title" );
	}

	public function getMenuGroups(){

		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "practica.agregar") );
		$menuOption->setPageName("PracticaAgregar");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/practicas_48.png" );
		$menuGroup->addMenuOption( $menuOption );
		
		
		return array($menuGroup);
	}
	
	public function getType(){
		
		return "Practicas";
		
	}	

	public function getModelClazz(){
		return get_class( new PracticaGridModel() );
	}

	public function getUicriteriaClazz(){
		return get_class( new UIPracticaCriteria() );
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("legend_operaciones", $this->localize("grid.operaciones") );
		$xtpl->assign("legend_resultados", $this->localize("grid.resultados") );
		
		$xtpl->assign("agregar_label", $this->localize("practica.agregar") );
	}

}
?>