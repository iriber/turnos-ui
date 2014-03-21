<?php

namespace Turnos\UI\components\form\cliente;

use Turnos\UI\utils\TurnosUtils;

use Rasty\Forms\form\Form;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\TipoDocumento;
use Turnos\Core\model\Sexo;
use Rasty\utils\LinkBuilder;

/**
 * Formulario para cliente

 * @author bernardo
 * @since 18/08/2013
 */
class ClienteQuickForm extends Form{
		
	

	/**
	 * 
	 * @var Cliente
	 */
	private $cliente;
	
	public function __construct(){

		parent::__construct();

		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		$this->addProperty("tipoDocumento");
		$this->addProperty("nroDocumento");
		$this->addProperty("fechaNacimiento");
		$this->addProperty("sexo");
		$this->addProperty("nroHistoriaClinica");
		$this->addProperty("telefonoFijo");
		
		$this->setLegend( $this->localize("cliente.agregar.legend") );
		
	}
	
	public function getOid(){
		
		//return RastyUtils::getParamGET("oid", RastyUtils::getParamPOST("oid"));
		return $this->getComponentById("oid")->getPopulatedValue( $this->getMethod() );
	}
	
	public function fillEntity($entity){
		
		parent::fillEntity($entity);
		
		//uppercase para el nombre.
		$entity->setNombre( strtoupper( $entity->getNombre() ) );
	}
	
	public function getType(){
		
		return "ClienteQuickForm";
		
	}

	public function getOnClickCancel(){

		$popupDivId = $this->getPopupDivId();
		return "closeFinderPopup('#$popupDivId');";
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		$this->fillFromSaved( $this->getCliente() );
		
		//rellenamos el nombre con el texto inicial
		$this->fillInput("nombre", $this->getInitialText() );
		
		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_nombre", $this->localize("cliente.nombre") );
		$xtpl->assign("lbl_tipoDocumento", $this->localize("cliente.tipoDocumento") );
		$xtpl->assign("lbl_nroDocumento", $this->localize("cliente.nroDocumento") );
		$xtpl->assign("lbl_sexo", $this->localize("cliente.sexo") );
		$xtpl->assign("lbl_fechaNacimiento", $this->localize("cliente.fechaNacimiento") );
		$xtpl->assign("lbl_nroHistoriaClinica", $this->localize("cliente.nroHistoriaClinica") );

		$xtpl->assign("lbl_cancel", $this->localize( "form.cancelar" ) );
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
	
}
?>