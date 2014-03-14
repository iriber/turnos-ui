<?php

namespace Turnos\UI\components\filter\practica;

use Turnos\UI\components\filter\model\UIPracticaCriteria;

use Turnos\UI\components\grid\model\PracticaGridModel;

use Rasty\Grid\filter\Filter;
use Rasty\utils\XTemplate;
use Rasty\utils\LinkBuilder;

/**
 * Filtro para buscar practicas
 * 
 * @author bernardo
 * @since 09/03/2014
 */
class PracticaFilter extends Filter{
		
	public function getType(){
		
		return "PracticaFilter";
	}
	

	public function __construct(){
		
		parent::__construct();
		
		$this->setGridModelClazz( get_class( new PracticaGridModel() ));
		
		$this->setUicriteriaClazz( get_class( new UIPracticaCriteria()) );
		
		//$this->setSelectRowCallback("seleccionarPractica");
		
		//agregamos las propiedades a popular en el submit.
		$this->addProperty("fechaDesde");
		$this->addProperty("fechaHasta");
		
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_fechaDesde",  $this->localize("practica.fechaDesde") );
		$xtpl->assign("lbl_fechaHasta",  $this->localize("practica.fechaHasta") );
		
		//$xtpl->assign("linkSeleccionar",  LinkBuilder::getPageUrl( "HistoriaClinica") );
		$xtpl->assign("linkSeleccionar",  LinkBuilder::getPageUrl( "PracticaModificar") );
		
		
	}
}
?>