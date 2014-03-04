<?php
namespace Turnos\UI\pages\nomenclador\agregar;

use Turnos\UI\pages\TurnosPage;

use Rasty\utils\XTemplate;
use Turnos\Core\model\Nomenclador;
use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

class NomencladorAgregar extends TurnosPage{

	/**
	 * Nomenclador a agregar.
	 * @var Nomenclador
	 */
	private $nomenclador;

	
	public function __construct(){
		
		//inicializamos el Nomenclador.
		$nomenclador = new Nomenclador();
		$this->setNomenclador($nomenclador);
		
		
	}
	
	public function getMenuGroups(){

		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "form.volver") );
		$menuOption->setPageName("Nomencladores");
		$menuGroup->addMenuOption( $menuOption );
		
		
		return array($menuGroup);
	}
	
	public function getTitle(){
		return $this->localize( "nomenclador.agregar.title" );
	}

	public function getType(){
		
		return "NomencladorAgregar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
		
	}

					
	public function getMsgError(){
		return "";
	}

	public function getNomenclador()
	{
	    return $this->nomenclador;
	}

	public function setNomenclador($nomenclador)
	{
	    $this->nomenclador = $nomenclador;
	}
}
?>