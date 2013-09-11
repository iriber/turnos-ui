<?php

namespace Turnos\UI\components\filter\localidad;

use Turnos\UI\components\filter\model\UILocalidadCriteria;

use Turnos\UI\components\grid\model\LocalidadGridModel;

use Rasty\Grid\filter\Filter;
use Rasty\utils\XTemplate;

/**
 * Filtro para buscar localidades
 * 
 * @author bernardo
 * @since 12/08/2013
 */
class LocalidadFilter extends Filter{
		
	public function getType(){
		
		return "LocalidadFilter";
	}
	

	public function __construct(){
		
		parent::__construct();
		
		$this->setGridModelClazz( get_class( new LocalidadGridModel() ));
		
		$this->setUicriteriaClazz( get_class( new UILocalidadCriteria()) );
		
		$this->setSelectRowCallback("seleccionarLocalidad");
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_nombre",  $this->localize("localidad.nombre") );
	}
}
?>