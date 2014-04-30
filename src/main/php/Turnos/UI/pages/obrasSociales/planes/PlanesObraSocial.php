<?php
namespace Turnos\UI\pages\obrasSociales\planes;

use Turnos\UI\service\finder\ObraSocialFinder;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Turnos\Core\model\ObraSocial;

use Rasty\utils\RastyUtils;
use Rasty\utils\XTemplate;

use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

use Rasty\utils\LinkBuilder;

class PlanesObraSocial extends TurnosPage{

	private $obraSocial;
	
	public function __construct(){

	}
	
	public function getMenuGroups(){

		$menuGroup = new MenuGroup();
		
		return array($menuGroup);
		
	}
	
	public function getTitle(){
		return $this->localize( "planes.obraSocial.title" );
	}

	public function getType(){
		
		return "PlanesObraSocial";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
		$xtpl->assign("planes_legend", $this->localize( "planes.planes_legend" ) );
		
		$nombreObraSocial = "";
		if( $this->getObraSocial() != null ){
			$nombreObraSocial = $this->getObraSocial()->getNombre();
		}
		
		$xtpl->assign("obraSocial_legend", $this->localize( "planes.obraSocial_legend" ) );
		$xtpl->assign("obraSocialNombre", $nombreObraSocial );
	}


	public function getMsgError(){
		return "";
	}
	
	public function setObraSocialOid($obraSocialOid)
	{
		if( !empty($obraSocialOid) ){
		
		    //a partir del id buscamos la obra social
			$obraSocial = UIServiceFactory::getUIObraSocialService()->get($obraSocialOid);
		
			$this->setObraSocial($obraSocial);
		}
	}
	
	public function getObraSocialFinderClazz(){
		
		return get_class( new ObraSocialFinder() );
		
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