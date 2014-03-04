<?php

namespace Turnos\UI\components\form\nomenclador;

use Turnos\UI\utils\TurnosUtils;

use Rasty\Forms\form\Form;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Turnos\Core\model\Nomenclador;
use Rasty\utils\LinkBuilder;

/**
 * Formulario para nomenclador

 * @author bernardo
 * @since 20/02/2014
 */
class NomencladorForm extends Form{
		
	
	/**
	 * label para el cancel
	 * @var string
	 */
	private $labelCancel;
	

	/**
	 * 
	 * @var Nomenclador
	 */
	private $nomenclador;
	
	public function __construct(){

		parent::__construct();
		$this->setLabelCancel("form.cancelar");

		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		$this->addProperty("codigo");
		
		$this->setBackToOnSuccess("Nomencladores");
		$this->setBackToOnCancel("Nomencladores");

		
	}
	
	public function getOid(){
		
		//return RastyUtils::getParamGET("oid", RastyUtils::getParamPOST("oid"));
		return $this->getComponentById("oid")->getPopulatedValue( $this->getMethod() );
	}
	
	public function fillEntity($entity){
		
		parent::fillEntity($entity);

		$input = $this->getComponentById("backSuccess");
		$value = $input->getPopulatedValue( $this->getMethod() );
		$this->setBackToOnSuccess($value);
		
	}
	
	public function getType(){
		
		return "NomencladorForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$this->fillFromSaved( $this->getNomenclador() );
		
		parent::parseXTemplate($xtpl);
		
		//$xtpl->assign("legend", $this->getLegend() );
		//$xtpl->assign("action", $this->getAction() );
		//$xtpl->assign("lbl_submit", $this->localize( $this->getLabelSubmit() ) );
		
		$xtpl->assign("cancel", $this->getLinkCancel() );
		$xtpl->assign("lbl_cancel", $this->localize( $this->getLabelCancel() ) );
		
		$xtpl->assign("lbl_nombre", $this->localize("nomenclador.nombre") );
		$xtpl->assign("lbl_codigo", $this->localize("nomenclador.codigo") );
				
		
		
	}

	public function getLabelCancel()
	{
	    return $this->labelCancel;
	}

	public function setLabelCancel($labelCancel)
	{
	    $this->labelCancel = $labelCancel;
	}


	public function getLinkCancel(){
		$params = array();
		
		$nomenclador = $this->getNomenclador();
		if( !empty( $nomenclador ) )
			$params["nomencladorOid"] = $nomenclador->getOid() ;			
			
			
		return LinkBuilder::getPageUrl( $this->getBackToOnCancel() , $params) ;
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