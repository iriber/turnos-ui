<?php

namespace Turnos\UI\components\form\nomenclador;

use Rasty\Forms\form\Form;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Turnos\Core\model\Nomenclador;
use Rasty\utils\LinkBuilder;

/**
 * Formulario para Nomenclador

 * @author bernardo
 * @since 31/08/2013
 */
class NomencladorQuickForm extends Form{
		
	
	/**
	 * 
	 * @var Nomenclador
	 */
	private $nomenclador;
	
	public function __construct(){

		parent::__construct();

		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		$this->addProperty("codigo");
		
		$this->setLegend( $this->localize("nomenclador.agregar.legend") );
	}
	
	public function getOid(){
		
		//return RastyUtils::getParamGET("oid", RastyUtils::getParamPOST("oid"));
		return $this->getComponentById("oid")->getPopulatedValue( $this->getMethod() );
	}
	
	public function fillEntity($entity){
		
		parent::fillEntity($entity);
		

	}
	
	public function getType(){
		
		return "NomencladorQuickForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$this->fillFromSaved( $this->getNomenclador() );
		
		//rellenamos el nombre con el texto inicial
		$this->fillInput("nombre", $this->getInitialText() );
		
		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_nombre", $this->localize("nomenclador.nombre") );
		$xtpl->assign("lbl_codigo", $this->localize("nomenclador.codigo") );
		
	}

	

	public function getNomenclador()
	{
	    return $this->nomenclador;
	}

	public function setNomenclador($nomenclador)
	{
	    $this->nomenclador = $nomenclador;
	}
}
?>