<?php

namespace Turnos\UI\components\header;

use Turnos\UI\utils\TurnosUtils;

use Rasty\security\RastySecurityContext;
use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;
use Rasty\utils\XTemplate;
use Rasty\utils\LinkBuilder;

use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;
use Rasty\Menu\menu\model\MenuActionOption;

class HeaderNavMetro extends HeaderNav{


	public function __construct(){
		parent::__construct();
	}
	
	public function getType(){
		
		return "HeaderNavMetro";
		
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		
		//$xtpl->assign("turnos_titulo", $this->localize("app.title"));
		$titles = array();
		$titles[] = $this->localize("app.title");
		$titles[] = $this->getTitle();
		
		//$xtpl->assign("mjpanel_titulo", implode(" / ", $titles));
		
		$xtpl->assign("menu_page", $this->localize("menu.page"));
		$xtpl->assign("menu_main", $this->localize("menu.main"));
		
		$xtpl->assign("reloadLabel", $this->localize("menu.main.reload"));
		
		if( TurnosUtils::isProfesionalLogged()) {
			$xtpl->assign("homeLabel", $this->localize("menu.main.panel"));
			$xtpl->assign("linkHome", LinkBuilder::getPageUrl( "ProfesionalHome") );
			$xtpl->parse("main.menu_home");	
		}else{
			$xtpl->assign("homeLabel", $this->localize("menu.main.turnos"));
			$xtpl->assign("linkHome", LinkBuilder::getPageUrl( "TurnosHome") );
			$xtpl->parse("main.menu_home");	
		}
		
		$user = RastySecurityContext::getUser();
		
		$xtpl->assign("user", $user->getName() );
		
		$this->parseMenuExit( $xtpl );
		//$this->parseMenuProfile( $xtpl, $user );
		
		//parseamos las opciones del menú principal.
		$this->parseMenuMain( $xtpl );
				
		//parseamos las opciones del menú de la página.
		$this->parseMenuPage( $xtpl );
		
		
	}
	
	public function parseMenu(XTemplate $xtpl, $title, $menuGroups, $blockName){

		foreach ($menuGroups as $menuGroup) {
			
			foreach ($menuGroup->getMenuOptions() as $menuOption) {

				$this->parseMenuOption($xtpl, $menuOption, "main.$blockName.menuGroup.menuOption");
			}
			$xtpl->parse("main.$blockName.menuGroup");
		}
		
		$xtpl->assign("menuLabel", $title );
		//$xtpl->assign("onclick", $this->getOnclick() );
		//$xtpl->assign("id", $this->getId() );
		
		$xtpl->parse("main.$blockName.menuTitle" );
			
		$xtpl->parse("main.$blockName" );
	}
	
	public function parseMenuPage(XTemplate $xtpl){
		
		if(count($this->getPageMenuGroups())>0)
			$this->parseMenu($xtpl, $this->localize("menu.page"), $this->getPageMenuGroups(), "menu_page");	
	}

	public function parseMenuMain(XTemplate $xtpl){
		
		$this->parseMenu($xtpl, $this->localize("menu.main"), $this->getMainMenuGroups(), "menu_main");	
	}
	
	public function parseMenuExit( XTemplate $xtpl){
		
		$menuOption = new MenuActionOption();
		$menuOption->setLabel( $this->localize( "menu.logout") );
		$menuOption->setActionName( "Logout");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/logout.png" );

		$this->parseMenuOption($xtpl, $menuOption, "main.menuOptionExit");
		
	}


	public function parseMenuProfile( XTemplate $xtpl, $user){
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "menu.profile") );
		$menuOption->setIconClass( "icon-cog" );
		$menuOption->setPageName( "UserProfile");
		$menuOption->addParam("oid",$user->getOid());
		$menuOption->setImageSource( $this->getWebPath() . "css/images/profile.png" );
		$this->parseMenuOption($xtpl, $menuOption, "main.menuOptionProfile");
		
	}
	
	public function parseMenuOption( XTemplate $xtpl, MenuOption $menuOption, $blockName){
//		$xtpl->assign("label", $menuOption->getLabel() );
//		$xtpl->assign("onclick", $menuOption->getOnclick());
//		$xtpl->assign("iconClass", $menuOption->getIconClass());
//		$xtpl->parse("$blockName");
//		
		$xtpl->assign("label", $menuOption->getLabel() );
		$xtpl->assign("onclick", $menuOption->getOnclick());
		$img = $menuOption->getImageSource();
		if(!empty($img)){
			$xtpl->assign("src", $img );
			$xtpl->parse("$blockName.image");
		}
		$xtpl->assign("iconClass", $menuOption->getIconClass());
		
		$xtpl->parse("$blockName");
	}
}
?>