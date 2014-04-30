<?php

namespace Turnos\UI\components\form\practica;

use Turnos\UI\service\UIServiceFactory;

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
use Turnos\Core\model\ClienteObraSocial;
use Turnos\Core\model\Profesional;
use Turnos\Core\model\TipoAfiliadoObraSocial;

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
	
	private $clienteObraSocial;
	
	public function __construct(){

		parent::__construct();
		$this->setLabelCancel("form.cancelar");
		
		$this->addProperty("fecha");
		$this->addProperty("cliente");
		$this->addProperty("profesional");

//		$this->addProperty("obraSocial");
//		$this->addProperty("nroObraSocial");
//		$this->addProperty("tipoAfiliado");
		$this->addProperty("obraSocial", "clienteObraSocial");
		$this->addProperty("nroObraSocial", "clienteObraSocial");
		$this->addProperty("tipoAfiliado", "clienteObraSocial");
		
		$this->addProperty("nomenclador");
		$this->addProperty("observaciones");
		
		$this->setBackToOnSuccess("HistoriaClinica");
		$this->setBackToOnCancel("HistoriaClinica");
		
		$this->clienteObraSocial = new ClienteObraSocial();
	}
	
	public function getOid(){
		
		//return RastyUtils::getParamGET("oid", RastyUtils::getParamPOST("oid"));
		return $this->getComponentById("oid")->getPopulatedValue( $this->getMethod() );
	}
	
	public function fillEntity($entity){
		
		parent::fillEntity($entity);
		
		$planOid = $this->getComponentById("planObraSocial")->getPopulatedValue( $this->getMethod() );
		if(!empty($planOid)){
			$this->clienteObraSocial->setPlanObraSocial( UIServiceFactory::getUIPlanObraSocialService()->get($planOid) );
		}
		
		$this->fillRelatedEntity("clienteObraSocial", $this->clienteObraSocial );

		$this->clienteObraSocial->setCliente($entity->getCliente());
		
		$entity->setClienteObraSocial($this->clienteObraSocial);
		
		
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
		$xtpl->assign("lbl_nroObraSocial", $this->localize("practica.nroObraSocial") );
		$xtpl->assign("lbl_tipoAfiliado", $this->localize("practica.tipoAfiliado") );
		$xtpl->assign("lbl_planObraSocial", $this->localize("practica.planObraSocial") );
		$xtpl->assign("buscar_obraSocial_title", $this->localize("practica.buscarClienteObraSocial.title") );
		
		$plan = $this->getPractica()->getPlanObraSocial();
		if($plan!=null)
			$xtpl->assign("planObraSocialOid", $plan->getOid() );
		
		
		$xtpl->assign("lbl_nomenclador", $this->localize("practica.nomenclador") );
		$xtpl->assign("lbl_observaciones", $this->localize("practica.observaciones") );
		
		$xtpl->assign("btn_verObrasSociales", $this->localize("practica.clienteObraSocial.buscar") );	
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
	    
	    $this->getClienteObraSocial()->setObraSocial($practica->getObraSocial());
		$this->getClienteObraSocial()->setNroObraSocial($practica->getNroObraSocial());
		$this->getClienteObraSocial()->setPlanObraSocial($practica->getPlanObraSocial());
		$this->getClienteObraSocial()->setTipoAfiliado($practica->getTipoAfiliado());
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
	
	public function getTiposAfiliado(){
		
		$tipos[-1] = $this->localize("tipoAfiliado.elegir");
		
		$tipos = array_merge($tipos, TurnosUtils::localizeEntities(TipoAfiliadoObraSocial::getItems()));
		
		return $tipos;	
		
	}
	
	

	public function getClienteObraSocial()
	{
	    return $this->clienteObraSocial;
	}

	public function setClienteObraSocial($clienteObraSocial)
	{
	    $this->clienteObraSocial = $clienteObraSocial;
	}
	
	public function getPlanesObraSocial(){
		
		$os = $this->getPractica()->getObraSocial();
		
		$planesArray = array();
		$planesArray[-1] = $this->localize("planObraSocial.elegir");;
		if( !empty($os) && $os!= null && $os->getOid()!=null ){
			$planes = UIServiceFactory::getUIPlanObraSocialService()->getPlanes($os);
			foreach ($planes as $plan) {
				$planesArray[$plan->getOid()] = $plan->getNombre();
			}
		}	
		
		return $planesArray;
	}
	
	
}
?>