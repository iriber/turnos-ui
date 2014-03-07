<?php
namespace Turnos\UI\pages\horarios;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\service\finder\ProfesionalFinder;

use Turnos\Core\model\Ausencia;
use Turnos\Core\model\Profesional;

use Rasty\utils\RastyUtils;
use Rasty\utils\XTemplate;

use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

use Rasty\utils\LinkBuilder;

class HorariosProfesional extends TurnosPage{

	private $profesional;
	
	public function __construct(){

		if( TurnosUtils::isProfesionalLogged() )
			$this->setProfesional( TurnosUtils::getProfesionalLogged() );
//		else 
//			$this->setProfesional( new Profesional() );
		
	}
	
	public function getMenuGroups(){

		$menuGroup = new MenuGroup();
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "ausencia.agregar") );
		$menuOption->setPageName("AusenciaAgregar");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/ausencias_48.png" );
		$menuGroup->addMenuOption( $menuOption );
		
		
		return array($menuGroup);
		
	}
	
	public function getTitle(){
		return $this->localize( "horarios.profesional.title" );
	}

	public function getType(){
		
		return "HorariosProfesional";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
		$xtpl->assign("profesional_legend", $this->localize( "horarios.profesional_legend" ) );
		
		$nombreProfesional = "";
		if( $this->getProfesional() != null ){
			$nombreProfesional = $this->getProfesional()->__toString();
		}
		
		$xtpl->assign("horarios_legend", $this->localize( "horarios.horarios_legend" ) );
		$xtpl->assign("profesionalNombre", $nombreProfesional );
	}


	public function getMsgError(){
		return "";
	}
	
	public function setProfesionalOid($profesionalOid)
	{
		if( !empty($profesionalOid) ){
		
		    //a partir del id buscamos el profesional
			$profesional = UIServiceFactory::getUIProfesionalService()->get($profesionalOid);
		
			$this->setProfesional($profesional);
		}
	}
	
	public function getProfesional()
	{
	    return $this->profesional;
	}

	public function setProfesional($profesional)
	{
	    $this->profesional = $profesional;
	}
	
	public function getProfesionalFinderClazz(){
		
		return get_class( new ProfesionalFinder() );
		
	}
	
}
?>