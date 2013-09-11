<?php

namespace Turnos\UI\components\form\practica;

use Turnos\UI\service\finder\NomencladorFinder;

use Turnos\UI\service\finder\ProfesionalFinder;

use Turnos\UI\utils\TurnosUtils;

use Rasty\Forms\form\Form;
use Turnos\UI\service\finder\ObraSocialFinder;
use Turnos\UI\service\finder\ClienteFinder;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\Practica;
use Turnos\Core\model\ObraSocial;
use Turnos\Core\model\Profesional;

use Rasty\utils\LinkBuilder;

/**
 * Formulario para practica

 * @author bernardo
 * @since 15/08/2013
 */
class PracticaForm extends Form{
		
	

	/**
	 * label para el cancel
	 * @var string
	 */
	private $labelCancel;
	

	/**
	 * 
	 * @var Practica
	 */
	private $practica;
	
	public function __construct(){

		parent::__construct();
		$this->setLabelCancel("form.cancelar");
		
		$this->addProperty("fecha");
		$this->addProperty("cliente");
		$this->addProperty("profesional");
		$this->addProperty("obraSocial");
		$this->addProperty("nomenclador");
		$this->addProperty("observaciones");
		
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
		
		return "PracticaForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		parent::parseXTemplate($xtpl);
		
		
		$xtpl->assign("cancel", $this->getLinkCancel() );
		$xtpl->assign("lbl_cancel", $this->localize( $this->getLabelCancel() ) );
		
		$xtpl->assign("lbl_fecha", $this->localize("practica.fecha") );
		$xtpl->assign("lbl_profesional", $this->localize("practica.profesional") );
		$xtpl->assign("lbl_cliente", $this->localize("practica.cliente") );
		$xtpl->assign("lbl_obraSocial", $this->localize("practica.obraSocial") );
		$xtpl->assign("lbl_nomenclador", $this->localize("practica.nomenclador") );
		$xtpl->assign("lbl_observaciones", $this->localize("practica.observaciones") );
	}


	public function getLabelCancel()
	{
	    return $this->labelCancel;
	}

	public function setLabelCancel($labelCancel)
	{
	    $this->labelCancel = $labelCancel;
	}

	public function getObraSocialFinderClazz(){
		
		return get_class( new ObraSocialFinder() );
		
	}
	
	public function getClienteFinderClazz(){
		
		return get_class( new ClienteFinder() );
		
	}	

	public function getProfesionalFinderClazz(){
		
		return get_class( new ProfesionalFinder() );
		
	}
	
	public function getNomencladorFinderClazz(){
		
		return get_class( new NomencladorFinder() );
		
	}	
	
	public function getPractica()
	{
	    return $this->practica;
	}

	public function setPractica($practica)
	{
	    $this->practica = $practica;
	}
	
	
	public function getLinkHistoriaClinica(){
		
		return LinkBuilder::getPageUrl( "HistoriaClinica") . "?oid=" . $this->getPractica()->getCliente()->getOid() ;
		
	}

	public function getLinkCancel(){
		$params = array();
		
		$cliente = $this->getPractica()->getCliente();
		if( !empty( $cliente ) )
			$params["clienteOid"] = $cliente->getOid() ;			
			
			
		return LinkBuilder::getPageUrl( $this->getBackToOnCancel() , $params) ;
	}
}
?>