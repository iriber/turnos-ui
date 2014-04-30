<?php
namespace Turnos\UI\pages\obrasSociales\cliente;

use Turnos\UI\service\finder\ClienteFinder;

use Turnos\UI\service\finder\ObraSocialFinder;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Turnos\Core\model\ObraSocial;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\TipoAfiliadoObraSocial;

use Rasty\utils\RastyUtils;
use Rasty\utils\XTemplate;

use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

use Rasty\utils\LinkBuilder;

class ClienteObrasSociales extends TurnosPage{

	private $cliente;
	private $selectRowCallback;
	private $popupDivId;
		
	public function __construct(){

	}
	
	public function getMenuGroups(){

		$menuGroup = new MenuGroup();
		
		return array($menuGroup);
		
	}
	
	public function getTitle(){
		return $this->localize( "cliente.obrasSociales.title" );
	}

	public function getType(){
		
		return "ClienteObrasSociales";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
		$xtpl->assign("obras_legend", $this->localize( "cliente.obrasSociales_legend" ) );
		
		$nombreCliente = "";
		if( $this->getCliente() != null ){
			$nombreCliente = $this->getCliente()->__toString();
		}
		
		$xtpl->assign("clienteNombre", $nombreCliente );
		
		$xtpl->assign("selectRowCallback", $this->getSelectRowCallback() );
		$xtpl->assign("popupDivId", $this->getPopupDivId() );
		
		$xtpl->assign("label_close", $this->localize("filter.close.label") );
		$xtpl->assign("legend_opciones", $this->localize("filter.opciones") );
		
		//obtenemos los obras sociales del cliente.
		$obras = UIServiceFactory::getUIClienteService()->getObrasSociales($this->getCliente());
		$this->parseObrasSociales($xtpl, $obras);
		
		
	}

	protected function parseObrasSociales(XTemplate $xtpl, $obras){

		$xtpl->assign("nroObraSocial_label", $this->localize( "cliente.nroObraSocial" ) );
		$xtpl->assign("tipoAfiliado_label", $this->localize( "cliente.tipoAfiliado" ) );
		$xtpl->assign("obraSocialNombre_label", $this->localize( "cliente.obraSocial" ) );
		$xtpl->assign("planObraSocialNombre_label", $this->localize( "cliente.planObraSocial" ) );
		
		foreach ($obras as $clienteObraSocial) {

			$os = $clienteObraSocial->getObraSocial();
			$plan = $clienteObraSocial->getPlanObraSocial();
			
			$osNombre = "";
			$osOid = "";
			if( !empty($os)){
				$osNombre = $os->getNombre();
				$osOid = $os->getOid();
			}
			$planOid = "";
			$planNombre = "";
			if(!empty($plan)){
				$planOid = $plan->getOid();
				$planNombre = $plan->getNombre();	
			}
			$xtpl->assign("clienteObraSocialOid", $clienteObraSocial->getOid() );
			$xtpl->assign("nroObraSocial", $clienteObraSocial->getNroObraSocial() );
			$xtpl->assign("tipoAfiliado", $clienteObraSocial->getTipoAfiliado() );
			
			$tipoAfiliadoNombre = TipoAfiliadoObraSocial::getLabel($clienteObraSocial->getTipoAfiliado());
			if(!empty($tipoAfiliadoNombre))
				$tipoAfiliadoNombre = $this->localize( $tipoAfiliadoNombre );
			
			$xtpl->assign("tipoAfiliadoNombre",  $tipoAfiliadoNombre );
			$xtpl->assign("obraSocialOid", $osOid );
			$xtpl->assign("obraSocialNombre", $osNombre );	
			$xtpl->assign("planObraSocialOid", $planOid );
			$xtpl->assign("planObraSocialNombre", $planNombre );
			
			$xtpl->parse("main.obrasSociales.obraSocial" );
		}
		
		
		if( count($obras) == 1 ){
			$xtpl->parse("main.seleccionarYCerrar" );
		}
	
		if( count($obras) == 0 ){
			//$xtpl->parse("main.cerrar" );
		}
		$xtpl->parse("main.obrasSociales" );
		
	}
	
	public function getMsgError(){
		return "";
	}
	
	public function setClienteOid($clienteOid)
	{
		if( !empty($clienteOid) ){
		
		    //a partir del id buscamos el cliente
			$cliente = UIServiceFactory::getUIClienteService()->get($clienteOid);
		
			$this->setCliente($cliente);
		}
	}
	
		public function getCliente()
	{
	    return $this->cliente;
	}

	public function setCliente($cliente)
	{
	    $this->cliente = $cliente;
	}

	public function getSelectRowCallback()
	{
	    return $this->selectRowCallback;
	}

	public function setSelectRowCallback($selectRowCallback)
	{
	    $this->selectRowCallback = $selectRowCallback;
	}

	public function getPopupDivId()
	{
	    return $this->popupDivId;
	}

	public function setPopupDivId($popupDivId)
	{
	    $this->popupDivId = $popupDivId;
	}
}
?>