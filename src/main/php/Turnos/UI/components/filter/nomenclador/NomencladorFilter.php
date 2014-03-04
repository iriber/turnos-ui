<?php

namespace Turnos\UI\components\filter\nomenclador;

use Turnos\UI\components\filter\model\UINomencladorCriteria;

use Turnos\UI\components\grid\model\NomencladorGridModel;

use Rasty\Grid\filter\Filter;
use Rasty\utils\XTemplate;
use Rasty\utils\LinkBuilder;

/**
 * Filtro para buscar nomenclador
 * 
 * @author bernardo
 * @since 15/08/2013
 */
class NomencladorFilter extends Filter{
		
	public function getType(){
		
		return "NomencladorFilter";
	}
	

	public function __construct(){
		
		parent::__construct();
		
		$this->setGridModelClazz( get_class( new NomencladorGridModel() ));
		
		$this->setUicriteriaClazz( get_class( new UINomencladorCriteria()) );
		
		//$this->setSelectRowCallback("seleccionarNomenclador");
		
		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		$this->addProperty("codigo");
		
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_nombre",  $this->localize("nomenclador.nombre") );
		
		$xtpl->assign("lbl_codigo",  $this->localize("nomenclador.codigo") );
		
	}
}
?>