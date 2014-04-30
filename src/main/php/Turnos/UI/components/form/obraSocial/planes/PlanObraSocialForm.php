<?php

namespace Turnos\UI\components\form\obraSocial\planes;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\service\finder\ObraSocialFinder;

use Turnos\UI\utils\TurnosUtils;

use Rasty\Forms\form\Form;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Turnos\Core\model\ObraSocial;
use Turnos\Core\model\PlanObraSocial;
use Rasty\utils\LinkBuilder;

/**
 * Formulario para PlanObraSocial

 * @author bernardo
 * @since 24/04/2014
 */
class PlanObraSocialForm extends Form{
		
	
	/**
	 * label para el cancel
	 * @var string
	 */
	private $labelCancel;
	

	/**
	 * 
	 * @var PlanObraSocial
	 */
	private $planObraSocial;
	
	public function __construct(){

		parent::__construct();
		$this->setLabelCancel("form.cancelar");

		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		$this->addProperty("obraSocial");
		
		$this->setBackToOnSuccess("PlanesObraSocial");
		$this->setBackToOnCancel("PlanesObraSocial");

		$this->planObraSocial = new PlanObraSocial();		
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
		
		//uppercase para el nombre.
		$entity->setNombre( strtoupper( $entity->getNombre() ) );
		
	}
	
	public function getType(){
		
		return "PlanObraSocialForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$this->fillFromSaved( $this->getPlanObraSocial() );
		
		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("cancel", $this->getLinkCancel() );
		$xtpl->assign("lbl_cancel", $this->localize( $this->getLabelCancel() ) );
		$xtpl->assign("plan_agregar_legend", $this->localize("planObraSocial.agregar.legend") );
		
		$xtpl->assign("lbl_nombre", $this->localize("planObraSocial.nombre") );
		$xtpl->assign("lbl_obraSocial", $this->localize("planObraSocial.obraSocial") );
				
		
		
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
		
		$obraSocial = $this->getPlanObraSocial()->getObraSocial();
		if( !empty( $obraSocial ) )
			$params["obraSocialOid"] = $obraSocial->getOid() ;			
			
			
		return LinkBuilder::getPageUrl( $this->getBackToOnCancel() , $params) ;
	}
	
	


	public function getPlanObraSocial()
	{
	    return $this->planObraSocial;
	}

	public function setPlanObraSocial($planObraSocial)
	{
	    $this->planObraSocial = $planObraSocial;
	}
	
	public function getObraSocialFinderClazz(){
		
		return get_class( new ObraSocialFinder() );
		
	}
	
	protected function initObserverEventType(){

		$this->addEventType( "ObraSocial" );
	}

	
	public function setObraSocialOid($obraSocialOid){
	    //a partir del id buscamos el profesional
		$obraSocial = UIServiceFactory::getUIObraSocialService()->get($obraSocialOid);
		
		$this->getPlanObraSocial()->setObraSocial($obraSocial);
	}
	
}
?>