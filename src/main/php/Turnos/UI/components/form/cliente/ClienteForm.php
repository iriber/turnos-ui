<?php

namespace Turnos\UI\components\form\cliente;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\utils\TurnosUtils;

use Rasty\Forms\form\Form;
use Turnos\UI\service\finder\ObraSocialFinder;
use Turnos\UI\service\finder\LocalidadFinder;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\TipoDocumento;
use Turnos\Core\model\Sexo;
use Turnos\Core\model\ObraSocial;
use Turnos\Core\model\PlanObraSocial;
use Turnos\Core\model\ClienteObraSocial;
use Turnos\Core\model\Localidad;
use Turnos\Core\model\TipoAfiliadoObraSocial;

use Rasty\utils\LinkBuilder;
/**
 * Formulario para cliente

 * @author bernardo
 * @since 06/08/2013
 */
class ClienteForm extends Form{
		
	
	/**
	 * label para el cancel
	 * @var string
	 */
	private $labelCancel;
	

	/**
	 * 
	 * @var Cliente
	 */
	private $cliente;

	private $clienteObraSocial;
	
	public function __construct(){

		parent::__construct();
		$this->setLabelCancel("form.cancelar");

		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		$this->addProperty("tipoDocumento");
		$this->addProperty("nroDocumento");
		$this->addProperty("fechaNacimiento");
		$this->addProperty("sexo");
		
//		$this->addProperty("nroHistoriaClinica");
//		$this->addProperty("obraSocial");
//		$this->addProperty("nroObraSocial");
//		$this->addProperty("tipoAfiliado");
		$this->addProperty("obraSocial", "clienteObraSocial");
		$this->addProperty("nroObraSocial", "clienteObraSocial");
		$this->addProperty("tipoAfiliado", "clienteObraSocial");
		
		$this->addProperty("telefonoFijo");
		$this->addProperty("telefonoMovil");
		$this->addProperty("email");
		$this->addProperty("domicilio");
		$this->addProperty("localidad");
		$this->addProperty("observaciones");
		
		$this->setBackToOnSuccess("Clientes");
		$this->setBackToOnCancel("Clientes");

		$this->clienteObraSocial = new ClienteObraSocial();
		
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
		
		$planOid = $this->getComponentById("planObraSocial")->getPopulatedValue( $this->getMethod() );
		if(!empty($planOid)){
			$this->clienteObraSocial->setPlanObraSocial( UIServiceFactory::getUIPlanObraSocialService()->get($planOid) );
		}
		
		$this->fillRelatedEntity("clienteObraSocial", $this->clienteObraSocial );

		$this->clienteObraSocial->setCliente($entity);
		
		$entity->setClienteObraSocial($this->clienteObraSocial);
		
		
		//uppercase para el nombre.
		$entity->setNombre( strtoupper( $entity->getNombre() ) );
	}
	
	public function getType(){
		
		return "ClienteForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$this->fillFromSaved( $this->getCliente() );
		
		parent::parseXTemplate($xtpl);
		
		//$xtpl->assign("legend", $this->getLegend() );
		//$xtpl->assign("action", $this->getAction() );
		//$xtpl->assign("lbl_submit", $this->localize( $this->getLabelSubmit() ) );
		
		$xtpl->assign("cancel", $this->getLinkCancel() );
		$xtpl->assign("lbl_cancel", $this->localize( $this->getLabelCancel() ) );
		
		$xtpl->assign("lbl_nombre", $this->localize("cliente.nombre") );
		$xtpl->assign("lbl_tipoDocumento", $this->localize("cliente.tipoDocumento") );
		$xtpl->assign("lbl_nroDocumento", $this->localize("cliente.nroDocumento") );
		$xtpl->assign("lbl_sexo", $this->localize("cliente.sexo") );
		$xtpl->assign("lbl_fechaNacimiento", $this->localize("cliente.fechaNacimiento") );
		$xtpl->assign("lbl_nroHistoriaClinica", $this->localize("cliente.nroHistoriaClinica") );
		
		$xtpl->assign("lbl_obraSocial", $this->localize("cliente.obraSocial") );
		$xtpl->assign("lbl_nroObraSocial", $this->localize("cliente.nroObraSocial") );
		$xtpl->assign("lbl_tipoAfiliado", $this->localize("cliente.tipoAfiliado") );
		
		$xtpl->assign("lbl_telefonoFijo", $this->localize("cliente.telefonoFijo") );
		$xtpl->assign("lbl_telefonoMovil", $this->localize("cliente.telefonoMovil") );
		$xtpl->assign("lbl_email", $this->localize("cliente.email") );
		$xtpl->assign("lbl_domicilio", $this->localize("cliente.domicilio") );
		$xtpl->assign("lbl_localidad", $this->localize("cliente.localidad") );
		$xtpl->assign("lbl_observaciones", $this->localize("cliente.observaciones") );
		
		$xtpl->assign("lbl_planObraSocial", $this->localize("cliente.planObraSocial") );
		$xtpl->assign("buscar_obraSocial_title", $this->localize("cliente.buscarClienteObraSocial.title") );
		
		$plan = $this->getCliente()->getPlanObraSocial();
		if($plan!=null)
			$xtpl->assign("planObraSocialOid", $plan->getOid() );
		
		$xtpl->assign("btn_verObrasSociales", $this->localize("cliente.clienteObraSocial.buscar") );
		
	}

	public function getLabelCancel()
	{
	    return $this->labelCancel;
	}

	public function setLabelCancel($labelCancel)
	{
	    $this->labelCancel = $labelCancel;
	}

	public function getCliente()
	{
	    return $this->cliente;
	}

	public function setCliente($cliente)
	{
	    $this->cliente = $cliente;
	    
	    if(!empty($cliente)){
		    $this->getClienteObraSocial()->setObraSocial($cliente->getObraSocial());
			$this->getClienteObraSocial()->setNroObraSocial($cliente->getNroObraSocial());
			$this->getClienteObraSocial()->setPlanObraSocial($cliente->getPlanObraSocial());
			$this->getClienteObraSocial()->setTipoAfiliado($cliente->getTipoAfiliado());
	    }
	}
	
	public function getTiposDocumento(){
		
		return TurnosUtils::localizeEntities(TipoDocumento::getItems());
	}
	
	public function getSexos(){
		
		return TurnosUtils::localizeEntities(Sexo::getItems());
	}
	
	public function getObraSocialFinderClazz(){
		
		return get_class( new ObraSocialFinder() );
		
	}
	
	public function getLocalidadFinderClazz(){
		
		return get_class( new LocalidadFinder() );
		
	}

	public function getLinkCancel(){
		$params = array();
		
		$cliente = $this->getCliente();
		if( !empty( $cliente ) )
			$params["clienteOid"] = $cliente->getOid() ;			
			
			
		return LinkBuilder::getPageUrl( $this->getBackToOnCancel() , $params) ;
	}
	
	public function getTiposAfiliado(){
		
		$tipos[-1] = $this->localize("tipoAfiliado.elegir");
		
		$tipos = array_merge($tipos, TurnosUtils::localizeEntities(TipoAfiliadoObraSocial::getItems()));
		
		return $tipos;	
				
	}
	
	public function getPlanesObraSocial(){
		
		$os = $this->getClienteObraSocial()->getObraSocial();
		
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
	
	public function getClienteObraSocial()
	{
	    return $this->clienteObraSocial;
	}

	public function setClienteObraSocial($clienteObraSocial)
	{
	    $this->clienteObraSocial = $clienteObraSocial;
	}
	
}
?>