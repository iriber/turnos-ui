<?php

namespace Turnos\UI\layouts;

use Rasty\Layout\layout\Rasty\Layout;

use Rasty\utils\XTemplate;


class TurnosLoginLayout extends TurnosLayout{

	public function getXTemplate($file_template=null){
		return parent::getXTemplate( dirname(__DIR__) . "/layouts/TurnosLoginLayout.htm" );
	}

	public function getType(){
		
		return "TurnosLoginLayout";
		
	}	

}
?>