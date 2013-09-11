<?php
namespace Turnos\UI\pages\turnos;


use Turnos\UI\pages\TurnosPage;

use Turnos\UI\components\filter\model\UIProfesionalCriteria;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\service\UIProfesionalService;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\i18n\Locale;
use Rasty\utils\LinkBuilder;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;

use Rasty\Grid\filter\model\UICriteria;

use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

class ProfesionalHome extends TurnosPage{

	private $profesional;
	private $fecha;
	
	public function __construct(){
		
		$this->setProfesional(TurnosUtils::getProfesionalLogged());
		$this->setFecha( new \Datetime() );
	}

	
	protected function parseXTemplate(XTemplate $xtpl){

		$nombre = $this->getProfesional()->getNombre();
		$xtpl->assign("legend", TurnosUtils::formatMessage( $this->localize("profesional_home.legend"), array($nombre)) );
		$xtpl->assign("profesional", $nombre);

		//ayuda
		$xtpl->assign("ayuda_label", $this->localize( "ayuda" ) );
		$xtpl->assign("ayuda_msg", $this->localize( "ayuda.agenda.titulo" ) );
		$xtpl->assign("linkAyuda", LinkBuilder::getPageUrl( "AyudaTurnos") );
		
		$xtpl->assign("enSala_legend", $this->localize("profesional_home.enSala.legend") );
		$xtpl->assign("asignados_legend", $this->localize("profesional_home.asignados.legend") );
		$xtpl->assign("atendidos_legend", $this->localize("profesional_home.atendidos.legend") );
		$xtpl->assign("stats_legend", $this->localize("profesional_home.stats.legend") );
		
		$xtpl->assign("estadoTurnoEnCurso", EstadoTurno::EnCurso );
		$xtpl->assign("linkHistoriaClinica",  LinkBuilder::getPageUrl( "HistoriaClinica" ) );
	}
	
	public function getTitle(){
		$nombre = $this->getProfesional()->getNombre();
		return TurnosUtils::formatMessage( $this->localize("profesional_home.title"), array($nombre)) ;
	}

	public function getMenuGroups(){

		$menuGroup = new MenuGroup();
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "ausencia.agregar" ) );
		$menuOption->setPageName("AusenciaAgregar");
		
		$menuOption->setImageSource( $this->getWebPath() . "css/images/ausencias_48.png" );
		$menuGroup->addMenuOption( $menuOption );
		
		return array($menuGroup);
	}
		
	public function getType(){
		
		return "ProfesionalHome";
		
	}	

	

	public function getProfesional()
	{
	    return $this->profesional;
	}

	public function setProfesional($profesional)
	{
	    $this->profesional = $profesional;
	}

	public function getFecha()
	{
	    return $this->fecha;
	}

	public function setFecha($fecha)
	{
	    $this->fecha = $fecha;
	}
}
?>