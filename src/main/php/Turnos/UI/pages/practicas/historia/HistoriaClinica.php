<?php
namespace Turnos\UI\pages\practicas\historia;

use Turnos\UI\service\UITurnoService;

use Turnos\UI\service\finder\ClienteFinder;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;

use Rasty\utils\LinkBuilder;
use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

use Turnos\Core\model\TipoDocumento;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Cliente;

class HistoriaClinica extends TurnosPage{

	private $cliente;
	
	private $backTo;
	
	
	public function __construct(){
		
		$this->setBackTo("ProfesionalHome");
	}
	
	public function getTitle(){
		
		$nombre = "";
		$cliente = $this->getCliente();
		if(!empty($cliente))
			$nombre = $this->getCliente()->getNombre();
			
		return TurnosUtils::formatMessage( $this->localize("practica.historia.title"), array($nombre)) ;
		
		//return $this->localize( "practica.historia.title" );
	}

	public function getMenuGroups(){

		$cliente = $this->getCliente();
		
		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();

		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "cliente.modificar" ) );
		$menuOption->setPageName("ClienteModificar");
		if(!empty($cliente) )
			$menuOption->addParam("oid",$this->getCliente()->getOid());
		$menuOption->addParam("backTo", "HistoriaClinica");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/clientes_48.png" );
		
		$menuGroup->addMenuOption( $menuOption );

		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "practica.agregar" ) );
		$menuOption->setPageName("PracticaAgregar");
		if(!empty($cliente) )
			$menuOption->addParam("clienteOid",$this->getCliente()->getOid());
		$menuOption->addParam("backTo", "HistoriaClinica");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/historia_48.png" );
		$menuGroup->addMenuOption( $menuOption );

		
		/*
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "turno.agregar" ) );
		$menuOption->setPageName("TurnoAgregar");
		if(!empty($cliente) )
			$menuOption->addParam("clienteOid",$this->getCliente()->getOid());
		$menuOption->addParam("backTo", "HistoriaClinica");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/turnos_48.png" );
		$menuGroup->addMenuOption( $menuOption );
		*/

		
		return array($menuGroup);
	}
		
	public function getType(){
		
		return "HistoriaClinica";
		
	}	

	
	protected function parseXTemplate(XTemplate $xtpl){
		
		$xtpl->assign("cliente_info_subtitle" ,  $this->localize("practica.historia.cliente_subtitle") );

		$xtpl->assign("linkOnTurnoFinalizado" , LinkBuilder::getPageUrl( $this->getBackTo() ));
		
		$xtpl->assign("ver_mas_datos_label" , $this->localize("historia.ver_mas_datos_personales") );
		$xtpl->assign("ver_menos_datos_label" , $this->localize("historia.ver_menos_datos_personales") );
		
		$xtpl->assign("cliente_label" , $this->localize("practica.cliente") );
		$xtpl->assign("obraSocial_label" , $this->localize("cliente.obraSocial") );
		$xtpl->assign("nroObraSocial_label" , $this->localize("cliente.nroObraSocial") );
		$xtpl->assign("fechaNacimiento_label" , $this->localize("cliente.fechaNacimiento") );
		$xtpl->assign("fechaAlta_label" , $this->localize("cliente.fechaAlta") );
		$xtpl->assign("edad_label" , $this->localize("cliente.edad") );
		$xtpl->assign("documento_label" , $this->localize("cliente.documento") );
		$xtpl->assign("domicilio_label" , $this->localize("cliente.domicilio") );
		$xtpl->assign("telefonos_label" , $this->localize("cliente.telefonos") );
		$xtpl->assign("email_label" , $this->localize("cliente.email") );
		
		$agregarPracticaParams = array();
				
		if( !empty( $this->cliente )){

			
			$xtpl->assign("cliente" , $this->getCliente()->getNombre() );
			$xtpl->assign("clienteOid" , $this->getCliente()->getOid() );
			$xtpl->assign("nroHistoriaClinica" , $this->getCliente()->getNroHistoriaClinica() );
			$xtpl->assign("fechaAlta" , TurnosUtils::formatDateToView($this->getCliente()->getFechaAlta()) );
			$xtpl->assign("fechaNacimiento" , TurnosUtils::formatDateToView($this->getCliente()->getFechaNacimiento()) );
			$xtpl->assign("edad" , TurnosUtils::formatEdad( TurnosUtils::getEdad( $this->getCliente()->getFechaNacimiento()) ));
			
			$os = $this->getCliente()->getObraSocial();
			if(!empty($os))
				$xtpl->assign("obraSocial" , $this->getCliente()->getObraSocial()->getNombre() );
			$xtpl->assign("nroObraSocial" , $this->getCliente()->getNroObraSocial() );

			$xtpl->assign("telefonoFijo" , $this->getCliente()->getTelefonoFijo() );
			$xtpl->assign("telefonoMovil" , $this->getCliente()->getTelefonoMovil() );
			$xtpl->assign("email" , $this->getCliente()->getEmail() );
			$xtpl->assign("localidad" , $this->getCliente()->getLocalidad() );
			$xtpl->assign("domicilio" , $this->getCliente()->getDomicilio() );
			$xtpl->assign("nroDocumento" , $this->getCliente()->getNroDocumento() );
			$xtpl->assign("tipoDocumento" , TipoDocumento::getLabel($this->getCliente()->getTipoDocumento()) );
			
			
			//si el cliente tiene un turno en curso, lo indicamos.
			$turno = $this->getTurnoEnCurso( $this->getCliente() );
			if(!empty($turno)){
				$xtpl->assign("turno_oid",  $turno->getOid() );
				$xtpl->assign("estado", EstadoTurno::getLabel($turno->getEstado()) );
				$xtpl->assign("fecha", TurnosUtils::formatDateToView( $turno->getFecha() ) );
				$xtpl->assign("hora", TurnosUtils::formatTimeToView( $turno->getHora() ) );
				
				$xtpl->assign("linkFinalizar",  LinkBuilder::getActionAjaxUrl( "FinalizarTurno") );
				$xtpl->assign("turno_finalizar_label" , $this->localize("turno.finalizar"));
				$xtpl->assign("paciente_atendido_por" , TurnosUtils::formatMessage( $this->localize("practica.historia.legend"), array($turno->getProfesional()->__toString())) );
				
				$xtpl->parse("main.turnoEnCurso");				
			}

			//parámetros para agregarle una práctica
			$agregarPracticaParams = array("clienteOid" => $this->getCliente()->getOid());
		
		}
		
		$xtpl->assign("practica_agregar_label" , $this->localize( "practica.agregar" ) );
		$xtpl->assign("linkAgregarPractica" , LinkBuilder::getPageUrl( "PracticaAgregar", $agregarPracticaParams ));
		
		$xtpl->assign("historia_info_subtitle" , $this->localize("practica.historia.practicas_subtitle") );
		
		
		$xtpl->assign("turnos_info_subtitle" , $this->localize("practica.historia.turnos_subtitle") );
		
		$xtpl->assign("resumenes_info_subtitle" , $this->localize("practica.historia.resumenes_subtitle") );
		
		
		
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
	
	public function getClienteFinderClazz(){
		
		return get_class( new ClienteFinder() );
		
	}	
	
	public function getTurnoEnCurso( Cliente $cliente ){

		return UITurnoService::getInstance()->getTurnoEnCursoCliente($cliente);
	}

	public function getBackTo()
	{
	    return $this->backTo;
	}

	public function setBackTo($backTo)
	{
		if(!empty($backTo))
	    	$this->backTo = $backTo;
	}
}
?>