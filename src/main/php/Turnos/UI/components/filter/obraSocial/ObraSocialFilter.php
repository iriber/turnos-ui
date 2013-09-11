<?php

namespace Turnos\UI\components\filter\obraSocial;

use Turnos\UI\components\filter\model\UIObraSocialCriteria;

use Turnos\UI\components\grid\model\ObraSocialGridModel;

use Rasty\Grid\filter\Filter;
use Rasty\utils\XTemplate;

/**
 * Filtro para buscar obras sociales
 * 
 * @author bernardo
 * @since 12/08/2013
 */
class ObraSocialFilter extends Filter{
		
	public function getType(){
		
		return "ObraSocialFilter";
	}
	

	public function __construct(){
		
		parent::__construct();
		
		$this->setGridModelClazz( get_class( new ObraSocialGridModel() ));
		
		$this->setUicriteriaClazz( get_class( new UIObraSocialCriteria()) );
		
		$this->setSelectRowCallback("seleccionarObraSocial");
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_nombre",  $this->localize("obraSocial.nombre") );
		$xtpl->assign("lbl_codigo",  $this->localize("obraSocial.codigo") );
	}
}
?>