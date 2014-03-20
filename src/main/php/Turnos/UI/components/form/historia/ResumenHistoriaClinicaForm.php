<?php

namespace Turnos\UI\components\form\historia;

use Turnos\UI\service\finder\ProfesionalFinder;

use Turnos\UI\utils\TurnosUtils;

use Rasty\Forms\form\Form;
use Turnos\UI\service\finder\ClienteFinder;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\ResumenHistoriaClinica;
use Turnos\Core\model\Profesional;

use Rasty\utils\LinkBuilder;

/**
 * Formulario para ResumenHistoriaClinica

 * @author bernardo
 * @since 19/03/2014
 */
class ResumenHistoriaClinicaForm extends Form{
		
	

	/**
	 * label para el cancel
	 * @var string
	 */
	private $labelCancel;
	

	/**
	 * @var ResumenHistoriaClinica
	 */
	private $resumenHistoriaClinica;
	
	public function __construct(){

		parent::__construct();
		$this->setLabelCancel("form.cancelar");
		
		$this->addProperty("fecha");
		$this->addProperty("cliente");
		$this->addProperty("profesional");
		$this->addProperty("texto");
		
		$this->setBackToOnSuccess("HistoriaClinica");
		$this->setBackToOnCancel("HistoriaClinica");
	}
	
	public function getOid(){
		
		//return RastyUtils::getParamGET("oid", RastyUtils::getParamPOST("oid"));
		return $this->getComponentById("oid")->getPopulatedValue( $this->getMethod() );
	}
	
	public function fillEntity($entity){
		
		parent::fillEntity($entity);
		
	}
	
	public function getType(){
		
		return "ResumenHistoriaClinicaForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		parent::parseXTemplate($xtpl);
		
		
		$xtpl->assign("cancel", $this->getLinkCancel() );
		$xtpl->assign("lbl_cancel", $this->localize( $this->getLabelCancel() ) );
		
		$xtpl->assign("lbl_fecha", $this->localize("resumenHistoriaClinica.fecha") );
		$xtpl->assign("lbl_profesional", $this->localize("resumenHistoriaClinica.profesional") );
		$xtpl->assign("lbl_cliente", $this->localize("resumenHistoriaClinica.cliente") );
		$xtpl->assign("lbl_texto", $this->localize("resumenHistoriaClinica.texto") );
	}


	public function getLabelCancel()
	{
	    return $this->labelCancel;
	}

	public function setLabelCancel($labelCancel)
	{
	    $this->labelCancel = $labelCancel;
	}

	public function getClienteFinderClazz(){
		
		return get_class( new ClienteFinder() );
		
	}	

	public function getProfesionalFinderClazz(){
		
		return get_class( new ProfesionalFinder() );
		
	}
	
	public function getResumenHistoriaClinica()
	{
	    return $this->resumenHistoriaClinica;
	}

	public function setResumenHistoriaClinica($resumenHistoriaClinica)
	{
	    $this->resumenHistoriaClinica = $resumenHistoriaClinica;
	}
	
	
	public function getLinkHistoriaClinica(){
		
		return LinkBuilder::getPageUrl( "HistoriaClinica") . "?oid=" . $this->getResumenHistoriaClinica()->getCliente()->getOid() ;
		
	}

	public function getLinkCancel(){
		$params = array();
		
		$cliente = $this->getResumenHistoriaClinica()->getCliente();
		if( !empty( $cliente ) )
			$params["clienteOid"] = $cliente->getOid() ;			
			
			
		return LinkBuilder::getPageUrl( $this->getBackToOnCancel() , $params) ;
	}
}
?>