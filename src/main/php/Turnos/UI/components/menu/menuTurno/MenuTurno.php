<?php

namespace Turnos\UI\components\menu\menuTurno;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;
use Rasty\utils\LinkBuilder;
use Rasty\utils\XTemplate;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Turno;


/**
 * menú de opciones para un turno.
 * 
 * @author bernardo
 * @since 10/12/2013
 */
class MenuTurno extends RastyComponent{
		
	/**
	 * 
	 * @var Turno
	 */
	private $turno;

	
	public function getType(){
		
		return "MenuTurno";
		
	}
	
	
	protected function parseXTemplate(XTemplate $xtpl){

		$turno = $this->getTurno();
		
		$xtpl->assign("linkBorrar",   LinkBuilder::getActionAjaxUrl( "BorrarTurno") );
		$xtpl->assign("borrar_label", self::localize( "turno.borrar" ) );
		
		
		$xtpl->assign("asignado_label", self::localize( "turno.asignado" ) );
		$xtpl->assign("linkAsignado",  LinkBuilder::getActionAjaxUrl( "TurnoAsignado") );
		
		$xtpl->assign("enSala_label", self::localize( "turno.enSala" ) );
		$xtpl->assign("linkEnSala",  LinkBuilder::getActionAjaxUrl( "TurnoEnSala") );
		
		
		$xtpl->assign("turno_oid", $turno->getOid() );
		
		$cliente = $turno->getCliente();
		if(!empty($cliente) && $cliente->getOid()>0){
			$xtpl->assign("cliente",  $turno->getCliente()->__toString() );
			$xtpl->assign("cliente_oid",  $turno->getCliente()->getOid());
			$xtpl->assign("menu_label",  $turno->getCliente()->__toString() );
			//TODO link a historia clínica del cliente.
			
			//TODO link a modificar el cliente.
			
		}else{
			$xtpl->assign("cliente", $turno->getNombre() );
			$xtpl->assign("menu_label", $turno->getNombre() );
		}
		
		
		if( $turno->getEstado() == EstadoTurno::EnSala ){
		
			$xtpl->parse("main.ensala");
				
		}elseif( $turno->getEstado() == EstadoTurno::Asignado ){
			$xtpl->parse("main.asignado");
		}else
			$xtpl->parse("main.otro_estado");
		
		
		$xtpl->assign("linkSeleccionarTurno",   LinkBuilder::getPageUrl( "TurnoModificar" , array("oid"=> $turno->getOid())) );
		$xtpl->assign("linkSeleccionarLabel",  self::localize("turno.editar") );
		
		
	}
	
	
	public function getTurno()
	{
	    return $this->turno;
	}

	public function setTurno($turno)
	{
	    $this->turno = $turno;
	}
}
?>