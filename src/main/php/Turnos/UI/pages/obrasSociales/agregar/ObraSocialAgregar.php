<?php
namespace Turnos\UI\pages\obrasSociales\agregar;

use Turnos\UI\pages\TurnosPage;

use Rasty\utils\XTemplate;
use Turnos\Core\model\ObraSocial;
use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

class ObraSocialAgregar extends TurnosPage{

	/**
	 * ObraSocial a agregar.
	 * @var ObraSocial
	 */
	private $obraSocial;

	
	public function __construct(){
		
		//inicializamos el ObraSocial.
		$obraSocial = new ObraSocial();
		$this->setObraSocial($obraSocial);
		
		
	}
	
	public function getMenuGroups(){

		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "form.volver") );
		$menuOption->setPageName("ObrasSociales");
		$menuGroup->addMenuOption( $menuOption );
		
		
		return array($menuGroup);
	}
	
	public function getTitle(){
		return $this->localize( "obraSocial.agregar.title" );
	}

	public function getType(){
		
		return "ObraSocialAgregar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
		
	}

					
	public function getMsgError(){
		return "";
	}

	public function getObraSocial()
	{
	    return $this->obraSocial;
	}

	public function setObraSocial($obraSocial)
	{
	    $this->obraSocial = $obraSocial;
	}
}
?>