<?php

namespace Turnos\UI\components\filter\profesional;


use Turnos\UI\components\filter\model\UIProfesionalCriteria;

use Rasty\Grid\filter\Filter;
use Rasty\utils\XTemplate;

/**
 * Filter para profesionales
 * 
 * @author bernardo
 * @since 14/08/2013
 */
class ProfesionalFilter extends Filter{
		
	public function getType(){
		
		return "ProfesionalFilter";
	}
	

	public function __construct(){
		
		parent::__construct();
		
		$this->setGridModelClazz( get_class( new ProfesionalGridModel() ));
		
		$this->setUicriteriaClazz( get_class( new UIProfesionalCriteria()) );
		
		$this->setSelectRowCallback("seleccionarProfesional");
		
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_nombre",  $this->localize("profesional.nombre") );
	}
}
?>