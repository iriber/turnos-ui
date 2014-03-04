<?php
namespace Turnos\UI\pages\obrasSociales\modificar;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\service\UIServiceFactory;

use Rasty\utils\XTemplate;
use Turnos\Core\model\ObraSocial;

class ObraSocialModificar extends TurnosPage{

	/**
	 * obraSocial a modificar.
	 * @var ObraSocial
	 */
	private $obraSocial;

	
	public function __construct(){
		
		//inicializamos el obraSocial.
		$obraSocial = new ObraSocial();
		$this->setObraSocial($obraSocial);

		
	}
	
	public function setObraSocialOid($oid){
		
		if(!empty($oid)){
			//a partir del id buscamos el obraSocial a modificar.
			$obraSocial = UIServiceFactory::getUIObraSocialService()->get($oid);
		
			$this->setObraSocial($obraSocial);
		}
		
	}
	
	public function getTitle(){
		return $this->localize( "obraSocial.modificar.title" );
	}

	public function getType(){
		
		return "ObraSocialModificar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
	}


	public function getObraSocial()
	{
	    return $this->obraSocial;
	}

	public function setObraSocial($obraSocial)
	{
	    $this->obraSocial = $obraSocial;
	}
	
}
?>