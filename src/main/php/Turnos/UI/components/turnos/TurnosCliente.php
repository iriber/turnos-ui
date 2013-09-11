<?php

namespace Turnos\UI\components\turnos;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;

use Rasty\utils\XTemplate;

use Turnos\Core\model\Cliente;
use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Rasty\utils\LinkBuilder;

/**
 * Turnos de un paciente.
 * 
 * @author bernardo
 * @since 15/08/2013
 */
class TurnosCliente extends RastyComponent{
		
	
	/**
	 * cliente del cual son los turnos que se muestran.
	 * @var Cliente
	 */
	private $cliente;

	private $finalizarTurnoCallback;
	
	public function __construct(){
		
		$this->setCliente( new Cliente() ); 
	}
	
	public function getType(){
		
		return "TurnosCliente";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$cantidad_ver = 1;
		
		if( empty( $this->cliente) )
			return;
		
			
		$turnos = UIServiceFactory::getUITurnoService()->getTurnosCliente($this->getCliente());
		
		
		$xtpl->assign("ver_mas_turnos_label" , $this->localize("historia.ver_mas_turnos") );
		$xtpl->assign("ver_menos_turnos_label" , $this->localize("historia.ver_menos_turnos") );
		
		
		$xtpl->assign("fecha_label", $this->localize( "agenda.fecha" ) );
		
		$xtpl->assign("hora_label",  $this->localize( "turno.hora" ) );
		$xtpl->assign("os_label",  $this->localize( "turno.obraSocial" ) );
		$xtpl->assign("estado_label",  $this->localize( "turno.estado" ) );
		$xtpl->assign("importe_label",  $this->localize( "turno.importe" ) );
		$xtpl->assign("profesional_label",  $this->localize( "turno.profesional" ) );

		$xtpl->assign("iniciar_label", $this->localize( "turno.iniciar" ) );
		$xtpl->assign("finalizar_label", $this->localize( "turno.finalizar" ) );

		$xtpl->assign("linkIniciar",  LinkBuilder::getActionAjaxUrl( "IniciarTurno") );
		$xtpl->assign("linkFinalizar",  LinkBuilder::getActionAjaxUrl( "FinalizarTurno") );
		
		$xtpl->assign("finalizarTurnoCallback",  $this->getFinalizarTurnoCallback() );
		
		$cantidad = 0;
		
		foreach ($turnos as $turno) {
			
			$cantidad++;
			
			if( $cantidad > $cantidad_ver ){
				
				//$xtpl->assign("visibilidadTurno", "display:none;");
				$xtpl->assign("visibilidadTurnoClass", "turnoOculto");
			}else{
				//$xtpl->assign("visibilidadTurno", "");
				$xtpl->assign("visibilidadTurnoClass", "turnoNoOculto");
			}
			
			$xtpl->assign("turno_css", TurnosUtils::getEstadoTurnoCss($turno->getEstado()));	
			$xtpl->assign("turno_oid",  $turno->getOid() );
			$xtpl->assign("cliente",  $this->getCliente()->__toString() );
			$xtpl->assign("cliente_oid",  $this->getCliente()->getOid() );
		
			$xtpl->assign("profesional",  $turno->getProfesional()->__toString() );
			$xtpl->assign("estado", EstadoTurno::getLabel($turno->getEstado()) );
			
			$os = $turno->getObraSocial();
			if( !empty($os) )
			$xtpl->assign("obra_social", $os->getNombre() );
			
			$xtpl->assign("importe", TurnosUtils::formatMontoToView( $turno->getImporte() ) );
			$xtpl->assign("fecha", TurnosUtils::formatDateToView( $turno->getFecha() ) );
			$xtpl->assign("hora", TurnosUtils::formatTimeToView( $turno->getHora() ) );
			
			if( $turno->getEstado() == EstadoTurno::EnCurso ){
				
				$xtpl->parse("main.turno.finalizar");
			}
		
			if( $turno->getEstado() == EstadoTurno::EnSala ){
				
				$xtpl->parse("main.turno.iniciar");
			}

			$xtpl->parse("main.turno");	
		}

		if( $cantidad > $cantidad_ver ){
			$xtpl->parse("main.ver_mas");
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

	public function getFinalizarTurnoCallback()
	{
	    return $this->finalizarTurnoCallback;
	}

	public function setFinalizarTurnoCallback($finalizarTurnoCallback)
	{
	    $this->finalizarTurnoCallback = $finalizarTurnoCallback;
	}

	protected function initObserverEventType(){

		$this->addEventType( "Cliente" );
		$this->addEventType( "Turno" );
	}
}
?>