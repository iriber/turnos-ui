<?php
namespace Turnos\UI\pages\horarios\agregar;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Rasty\utils\XTemplate;
use Turnos\Core\model\Ausencia;
use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

use Rasty\utils\LinkBuilder;

class AusenciaAgregar extends TurnosPage{

	private $ausencia;
	
	public function __construct(){

		$this->setAusencia(new Ausencia());
		
		if( TurnosUtils::isProfesionalLogged() )
			$this->ausencia->setProfesional( TurnosUtils::getProfesionalLogged() );
		
	}
	
	public function getMenuGroups(){

		$menuGroup = new MenuGroup();
		
		return array($menuGroup);
	}
	
	public function getTitle(){
		return $this->localize( "ausencia.agregar.title" );
	}

	public function getType(){
		
		return "AusenciaAgregar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
		
		$xtpl->assign("linkAusenciaAgregar", LinkBuilder::getPageUrl( "AusenciaAgregar" ) );
	}


	public function getMsgError(){
		return "";
	}
	
	public function setProfesionalOid($profesionalOid)
	{
		if( !empty($profesionalOid) ){
		
		    //a partir del id buscamos el profesional
			$profesional = UIServiceFactory::getUIProfesionalService()->get($profesionalOid);
		
			$this->getAusencia()->setProfesional($profesional);
		}
	}
	


	public function getAusencia()
	{
	    return $this->ausencia;
	}

	public function setAusencia($ausencia)
	{
	    $this->ausencia = $ausencia;
	}

}
?>