<?php

namespace Turnos\UI\components\form\cliente;

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
use Turnos\Core\model\Localidad;
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
	
	public function __construct(){

		parent::__construct();
		$this->setLabelCancel("form.cancelar");

		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		$this->addProperty("tipoDocumento");
		$this->addProperty("nroDocumento");
		$this->addProperty("fechaNacimiento");
		$this->addProperty("sexo");
		$this->addProperty("nroHistoriaClinica");
		$this->addProperty("obraSocial");
		$this->addProperty("nroObraSocial");
		$this->addProperty("telefonoFijo");
		$this->addProperty("telefonoMovil");
		$this->addProperty("email");
		$this->addProperty("domicilio");
		$this->addProperty("localidad");
		$this->addProperty("observaciones");
		
		$this->setBackToOnSuccess("Clientes");
		$this->setBackToOnCancel("Clientes");

		
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
		
		$xtpl->assign("lbl_telefonoFijo", $this->localize("cliente.telefonoFijo") );
		$xtpl->assign("lbl_telefonoMovil", $this->localize("cliente.telefonoMovil") );
		$xtpl->assign("lbl_email", $this->localize("cliente.email") );
		$xtpl->assign("lbl_domicilio", $this->localize("cliente.domicilio") );
		$xtpl->assign("lbl_localidad", $this->localize("cliente.localidad") );
		$xtpl->assign("lbl_observaciones", $this->localize("cliente.observaciones") );
		
		
		
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
	
	
}
?>