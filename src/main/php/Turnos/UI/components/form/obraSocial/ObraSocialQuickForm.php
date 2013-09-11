<?php

namespace Turnos\UI\components\form\obraSocial;

use Rasty\Forms\form\Form;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Turnos\Core\model\ObraSocial;
use Rasty\utils\LinkBuilder;

/**
 * Formulario para ObraSocial

 * @author bernardo
 * @since 20/08/2013
 */
class ObraSocialQuickForm extends Form{
		
	
	/**
	 * 
	 * @var ObraSocial
	 */
	private $obraSocial;
	
	public function __construct(){

		parent::__construct();

		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		$this->addProperty("codigo");
		
		$this->setLegend( $this->localize("obraSocial.agregar.legend") );
	}
	
	public function getOid(){
		
		//return RastyUtils::getParamGET("oid", RastyUtils::getParamPOST("oid"));
		return $this->getComponentById("oid")->getPopulatedValue( $this->getMethod() );
	}
	
	public function fillEntity($entity){
		
		parent::fillEntity($entity);
		

	}
	
	public function getType(){
		
		return "ObraSocialQuickForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$this->fillFromSaved( $this->getObraSocial() );
		
		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_nombre", $this->localize("obraSocial.nombre") );
		$xtpl->assign("lbl_codigo", $this->localize("obraSocial.codigo") );
		
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