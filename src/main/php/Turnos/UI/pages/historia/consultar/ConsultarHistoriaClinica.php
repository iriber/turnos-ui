<?php
namespace Turnos\UI\pages\historia\consultar;


use Turnos\UI\service\finder\ClienteFinder;

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

class ConsultarHistoriaClinica extends TurnosPage{

	private $cliente;
	
	public function __construct(){

		$this->cliente = null;
	}

	public function isSecure(){
		return false;
	}
	
	
	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("historia_legend", $this->localize("historiaClinica.quick.legend") );
		$xtpl->assign("lbl_historiaClinica", $this->localize("cliente.nroHistoriaClinica") );
		$xtpl->assign("lbl_cliente", $this->localize("turno.cliente") );
		
		
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
		
		return array();
	}
		
	public function getType(){
		
		return "ConsultarHistoriaClinica";
		
	}

	public function getClienteFinderClazz(){
		
		return get_class( new ClienteFinder() );
		
	}	
	

	public function getCliente()
	{
	    return $this->cliente;
	}

	public function setCliente($cliente)
	{
	    $this->cliente = $cliente;
	}
}
?>