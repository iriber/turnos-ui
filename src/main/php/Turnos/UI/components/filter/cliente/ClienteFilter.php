<?php

namespace Turnos\UI\components\filter\cliente;

use Turnos\UI\components\filter\model\UIClienteCriteria;

use Turnos\UI\components\grid\model\ClienteGridModel;

use Rasty\Grid\filter\Filter;
use Rasty\utils\XTemplate;
use Rasty\utils\LinkBuilder;

/**
 * Filtro para buscar clientes
 * 
 * @author bernardo
 * @since 12/08/2013
 */
class ClienteFilter extends Filter{
		
	public function getType(){
		
		return "ClienteFilter";
	}
	

	public function __construct(){
		
		parent::__construct();
		
		$this->setGridModelClazz( get_class( new ClienteGridModel() ));
		
		$this->setUicriteriaClazz( get_class( new UIClienteCriteria()) );
		
		//$this->setSelectRowCallback("seleccionarCliente");
		
		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		$this->addProperty("nroHistoriaClinica");
		$this->addProperty("obraSocialNombre");
		$this->addProperty("nroObraSocial");
		$this->addProperty("domicilio");
		
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		//rellenamos el nombre con el texto inicial
		$this->fillInput("nombre", $this->getInitialText() );
		
		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_nombre",  $this->localize("cliente.nombre") );
		$xtpl->assign("lbl_nroHistoriaClinica",  $this->localize("cliente.nroHistoriaClinica") );
		$xtpl->assign("lbl_obraSocialNombre",  $this->localize("cliente.obraSocial") );
		$xtpl->assign("lbl_nroObraSocial",  $this->localize("cliente.nroObraSocial") );
		$xtpl->assign("lbl_domicilio",  $this->localize("cliente.domicilio") );
		
		//$xtpl->assign("linkSeleccionar",  LinkBuilder::getPageUrl( "HistoriaClinica") );
		$xtpl->assign("linkSeleccionar",  LinkBuilder::getPageUrl( "ClienteModificar") );
		
		
	}
}
?>