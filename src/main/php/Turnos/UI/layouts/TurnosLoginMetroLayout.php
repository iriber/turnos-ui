<?php

namespace Turnos\UI\layouts;

use Rasty\Layout\layout\Rasty\Layout;

use Rasty\utils\XTemplate;


class TurnosLoginMetroLayout extends TurnosMetroLayout{

	public function getXTemplate($file_template=null){
		return parent::getXTemplate( dirname(__DIR__) . "/layouts/TurnosLoginMetroLayout.htm" );
	}

	public function getType(){
		
		return "TurnosLoginMetroLayout";
		
	}	

}
?>