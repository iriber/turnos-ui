<?php

namespace Turnos\UI\components\historia;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;

use Rasty\utils\XTemplate;

use Turnos\Core\model\Cliente;
use Rasty\utils\LinkBuilder;

/**
 * Resumen de Historia clínica de un paciente.
 * 
 * @author bernardo
 * @since 19/03/2014
 */
class ResumenHistoriaClinica extends RastyComponent{
		
	
	/**
	 * cliente del cual se muestra el resumen de la historia clínica.
	 * @var Cliente
	 */
	private $cliente;
	
	public function __construct(){
		
		$this->setCliente( new Cliente() ); 
	}
	
	public function getType(){
		
		return "ResumenHistoriaClinica";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		
		if( empty( $this->cliente) )
			return;
		
		$xtpl->assign("cliente",  $this->getCliente()->__toString() );
		$xtpl->assign("cliente_oid", $this->getCliente()->getOid() );
		//$xtpl->assign("cliente_oid", $this->getCliente()->getOid() );
		
		$xtpl->assign("linkModificar",  LinkBuilder::getPageUrl( "ResumenHistoriaClinicaModificar") );
		$xtpl->assign("linkBorrar",  LinkBuilder::getActionAjaxUrl( "BorrarResumenHistoriaClinica") );
		$xtpl->assign("linkAgregar",  LinkBuilder::getPageUrl( "ResumenHistoriaClinicaAgregar") );

		$xtpl->assign("agregarResumenHistoriaClinica_label", $this->localize("resumenHistoriaClinica.agregar") );
		$xtpl->assign("editar_label", $this->localize("resumenHistoriaClinica.modificar") );
		$xtpl->assign("borrar_label", $this->localize("resumenHistoriaClinica.borrar") );
		
		$xtpl->assign("resumen_lbl", $this->localize("resumenHistoriaClinica") ); 
		$xtpl->assign("fecha_lbl" , $this->localize("resumenHistoriaClinica.fecha") );
		$xtpl->assign("profesional_lbl", $this->localize("resumenHistoriaClinica.profesional") ); 
		
		$resumenes = UIServiceFactory::getUIResumenHistoriaClinicaService()->getResumenHistoriaClinica($this->getCliente());
		
		foreach ($resumenes as $resumen) {
			
			$xtpl->assign("resumen_oid", $resumen->getOid() );
			$xtpl->assign("cliente", $this->getCliente()->__toString() );
			$xtpl->assign("cliente_oid", $this->getCliente()->getOid() );
			
			$xtpl->assign("fecha" , TurnosUtils::formatDateToView($resumen->getFecha()) );
			
			$xtpl->assign("texto", $resumen->getTexto() );
			$xtpl->assign("profesional", $resumen->getProfesional() );
			
			$xtpl->parse("main.resumen");
		}
		
	}
	
	

	public function setClienteOid($clienteOid)
	{
		if(!empty($clienteOid) ){

			//a partir del id buscamos el cliente.
			$cliente = UIServiceFactory::getUIClienteService()->get($clienteOid);
		
			$this->setCliente($cliente);
		}
	    
		
	}

	public function getCliente()
	{
	    return $this->cliente;
	}

	public function setCliente($cliente)
	{
	    $this->cliente = $cliente;
	}
	
	protected function initObserverEventType(){

		$this->addEventType( "Cliente" );
		$this->addEventType( "ResumenHistoriaClinica" );
	}
}
?>