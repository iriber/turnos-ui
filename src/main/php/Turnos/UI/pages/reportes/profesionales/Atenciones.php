<?php
namespace Turnos\UI\pages\reportes\profesionales;


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

class Atenciones extends TurnosPage{

	private $fecha;
	
	public function __construct(){
		
		$this->setFecha( new \Datetime() );
	}

	
	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("reportes_legend", $this->localize("stats.profesional.reportes.legend") );
		$xtpl->assign("stats_legend", $this->localize("stats.profesional.pacientesDia.legend") );
		
		//reportes
		$this->parseReporte( $xtpl, $this->localize("stats.profesional.pacientesDia.legend"), "TotalesDia" );
		$this->parseReporte( $xtpl, $this->localize("stats.profesional.pacientesMes.legend"), "PacientesMes" );
		$this->parseReporte( $xtpl, $this->localize("stats.profesional.pacientesAnio.legend"), "AgendaTurnos" );
		
		
		
	}
	
	protected function parseReporte(XTemplate $xtpl, $titulo, $link){
		
		$xtpl->assign("titulo",  $titulo);
		$xtpl->assign("linkReporte",  $link);
		$xtpl->parse( "main.reporte" );
		
	}
	
	public function getTitle(){
		///$nombre = $this->getProfesional()->getNombre();
		return  $this->localize("stats.profesional.title")  ;
	}

	public function getMenuGroups(){

		$menuGroup = new MenuGroup();
		
//		$menuOption = new MenuOption();
//		$menuOption->setLabel( $this->localize( "ausencia.agregar" ) );
//		$menuOption->setPageName("AusenciaAgregar");
//		
//		$menuOption->setImageSource( $this->getWebPath() . "css/images/ausencias_48.png" );
//		$menuGroup->addMenuOption( $menuOption );
		
		return array();
	}
		
	public function getType(){
		
		return "Atenciones";
		
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