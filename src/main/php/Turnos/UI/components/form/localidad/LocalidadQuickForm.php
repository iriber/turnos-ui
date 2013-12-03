<?php

namespace Turnos\UI\components\form\localidad;

use Rasty\Forms\form\Form;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Turnos\Core\model\Localidad;
use Rasty\utils\LinkBuilder;

/**
 * Formulario para Localidad

 * @author bernardo
 * @since 20/08/2013
 */
class LocalidadQuickForm extends Form{
		
	
	/**
	 * 
	 * @var Localidad
	 */
	private $localidad;
	
	public function __construct(){

		parent::__construct();

		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		
		$this->setLegend( $this->localize("localidad.agregar.legend") );
	}
	
	public function getOid(){
		
		//return RastyUtils::getParamGET("oid", RastyUtils::getParamPOST("oid"));
		return $this->getComponentById("oid")->getPopulatedValue( $this->getMethod() );
	}
	
	public function fillEntity($entity){
		
		parent::fillEntity($entity);
		

	}
	
	public function getType(){
		
		return "LocalidadQuickForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$this->fillFromSaved( $this->getLocalidad() );
		
		//rellenamos el nombre con el texto inicial
		$this->fillInput("nombre", $this->getInitialText() );
		
		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_nombre", $this->localize("localidad.nombre") );
		
	}

	


	public function getLocalidad()
	{
	    return $this->localidad;
	}

	public function setLocalidad($localidad)
	{
	    $this->localidad = $localidad;
	}
}
?>