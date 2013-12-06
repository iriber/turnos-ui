<?php

namespace Turnos\UI\components\header;

use Turnos\UI\utils\TurnosUtils;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;
use Rasty\utils\XTemplate;

use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;
use Rasty\Menu\menu\model\MenuActionOption;

class HeaderNav extends RastyComponent{

	private $title;
	
	private $pageMenuGroups;

	public function __construct(){
		$this->pageMenuGroups = array();
		//$this->setTitle($this->localize("app.title"));
	}
	
	public function getType(){
		
		return "HeaderNav";
		
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		
		//$xtpl->assign("turnos_titulo", $this->localize("app.title"));
		$titles = array();
		$titles[] = $this->localize("app.title");
		$titles[] = $this->getTitle();
		
		$xtpl->assign("turnos_titulo", implode(" / ", $titles));
		
		$xtpl->assign("menu_page", $this->localize("menu.page"));
		$xtpl->assign("menu_main", $this->localize("menu.main"));
		
	}
	
	public function getMainMenuGroups(){
		
		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();

		if( TurnosUtils::isProfesionalLogged()) {
				
			$menuOption = new MenuOption();
			$menuOption->setLabel( $this->localize( "menu.profesional_home" ) );
			$menuOption->setPageName("ProfesionalHome");
			$menuOption->setImageSource( $this->getWebPath() . "css/images/profesional_home_48.png" );
			$menuGroup->addMenuOption( $menuOption );	
				
		}
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "menu.turnos") );
		$menuOption->setPageName( "TurnosHome" );
		$menuOption->setImageSource( $this->getWebPath() . "css/images/turnos_48.png" );
		$menuGroup->addMenuOption( $menuOption );
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "menu.clientes") );
		$menuOption->setImageSource( $this->getWebPath() . "css/images/clientes_48.png" );
		$menuOption->setPageName( "Clientes");
		$menuGroup->addMenuOption( $menuOption );
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "menu.nomenclador") );
		$menuOption->setImageSource( $this->getWebPath() . "css/images/nomenclador_48.png" );
		$menuOption->setPageName( "Nomencladores");
		$menuGroup->addMenuOption( $menuOption );
			
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "menu.obrasSociales") );
		$menuOption->setPageName( "ObrasSociales");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/obrassociales_48.png" );
		$menuGroup->addMenuOption( $menuOption );
		
		return array($menuGroup);
	}
	
	public function getPageMenuGroups(){
		
		return $this->pageMenuGroups;
	}

	public function setPageMenuGroups($pageMenuGroups)
	{
	    $this->pageMenuGroups = $pageMenuGroups;
	}

	public function getTitle()
	{
	    return $this->title;
	}

	public function setTitle($title)
	{
		if(!empty($title))
	    	$this->title = $title;
	}
}
?>