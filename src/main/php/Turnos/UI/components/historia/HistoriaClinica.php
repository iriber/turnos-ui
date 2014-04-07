<?php

namespace Turnos\UI\components\historia;

use Turnos\UI\render\historia\HistoriaClinicaPDFRenderer;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;

use Rasty\utils\XTemplate;

use Turnos\Core\model\Cliente;
use Rasty\utils\LinkBuilder;

/**
 * Historia clínica de un paciente.
 * 
 * @author bernardo
 * @since 15/08/2013
 */
class HistoriaClinica extends RastyComponent{
		
	
	/**
	 * cliente del cual se muestra la historia clínica.
	 * @var Cliente
	 */
	private $cliente;
	
	public function __construct(){
		
		$this->setCliente( new Cliente() ); 
	}
	
	public function getType(){
		
		return "HistoriaClinica";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		
		if( empty( $this->cliente) )
			return;
		
		$xtpl->assign("cliente",  $this->getCliente()->__toString() );
		$xtpl->assign("cliente_oid", $this->getCliente()->getOid() );
		//$xtpl->assign("cliente_oid", $this->getCliente()->getOid() );
		
		$xtpl->assign("linkModificar",  LinkBuilder::getPageUrl( "PracticaModificar") );
		$xtpl->assign("linkBorrar",  LinkBuilder::getActionAjaxUrl( "BorrarPractica") );
		$xtpl->assign("linkAgregar",  LinkBuilder::getPageUrl( "PracticaAgregar") );

		$xtpl->assign("agregarPractica_label", $this->localize("practica.agregar") );
		$xtpl->assign("editar_label", $this->localize("practica.modificar") );
		$xtpl->assign("borrar_label", $this->localize("practica.borrar") );
		
		$xtpl->assign("practica_lbl", $this->localize("practica") ); 
		$xtpl->assign("fecha_lbl" , $this->localize("practica.fecha") );
		$xtpl->assign("profesional_lbl", $this->localize("practica.profesional") ); 
		$xtpl->assign("obraSocial_lbl", $this->localize("practica.obraSocial") );
		
		$practicas = $this->getPracticas();
		
		foreach ($practicas as $practica) {
			
			$xtpl->assign("practica_oid", $practica->getOid() );
			$xtpl->assign("cliente", $this->getCliente()->__toString() );
			$xtpl->assign("cliente_oid", $this->getCliente()->getOid() );
			
			$xtpl->assign("fecha" , TurnosUtils::formatDateToView($practica->getFecha()) );
			$xtpl->assign("nomenclador_codigo", $practica->getNomenclador()->getCodigo() ); 
			$xtpl->assign("nomenclador_nombre", $practica->getNomenclador()->getNombre() ); 
			
			$os = $practica->getObraSocial();
			if(!empty($os))
			$xtpl->assign("obraSocial", $practica->getObraSocial()->getNombre() );
			
			$observaciones = str_replace("\n", "<br/>", $practica->getObservaciones());
			if(empty($observaciones))
				$observaciones = $this->localize("practica.completarObservaciones");
			$xtpl->assign("observaciones", $observaciones );
			
			$xtpl->assign("profesional", $practica->getProfesional() );
			
			$xtpl->parse("main.practica");
		}
		
	}
	
	public function getPracticas(){
		
		return UIServiceFactory::getUIPracticaService()->getHistoriaClinica($this->getCliente());
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
		$this->addEventType( "HistoriaClinica" );
	}
	
	public function getPDFRenderer(){
		
		$name = "Historia_Clinica";
		$cliente = $this->getCliente();
		if(!empty($cliente)){
			$name .= "_Nro_" . $cliente->getNroHistoriaClinica();
			$name .= "_" . $cliente->getNombre();
		}
		
		$renderer = new HistoriaClinicaPDFRenderer();
		$renderer->setName($name);
		
		return $renderer;
	}
}
?>