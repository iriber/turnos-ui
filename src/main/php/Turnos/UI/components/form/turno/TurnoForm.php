<?php

namespace Turnos\UI\components\form\turno;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\service\finder\ProfesionalFinder;

use Turnos\UI\utils\TurnosUtils;

use Rasty\Forms\form\Form;
use Turnos\UI\service\finder\ObraSocialFinder;
use Turnos\UI\service\finder\ClienteFinder;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\Turno;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\ObraSocial;
use Turnos\Core\model\Profesional;
use Turnos\Core\model\Prioridad;

use Rasty\utils\LinkBuilder;
use Rasty\utils\Logger;

/**
 * Formulario para turno

 * @author bernardo
 * @since 13/08/2013
 */
class TurnoForm extends Form{

	/**
	 * label para el cancel
	 * @var string
	 */
	private $labelCancel;
	
	private $fechaEditable;
	
	private $horaEditable;
	
	private $intervalo;
	
	private $estadoUrgente;
	
	/**
	 * 
	 * @var Turno
	 */
	private $turno;
	
	public function __construct(){

		parent::__construct();
		$this->setLabelCancel("form.cancelar");

		$this->setFechaEditable( true );
		$this->setHoraEditable( true );
		$this->setIntervalo(15);
		
		$this->addProperty("fecha");
		$this->addProperty("hora");
		$this->addProperty("cliente");
		$this->addProperty("profesional");
		$this->addProperty("obraSocial");
		$this->addProperty("nroObraSocial");
		$this->addProperty("importe");
		$this->addProperty("estado");
		$this->addProperty("prioridad");
		$this->addProperty("duracion");
		$this->addProperty("telefono");
		$this->addProperty("nombre");
		
		$this->setBackToOnSuccess("TurnosHome");
		$this->setBackToOnCancel("TurnosHome");	
		
		$this->setTurnoOid( RastyUtils::getParamGET("turnoOid",0));
		
	}
	
	public function getOid(){
		
		//return RastyUtils::getParamGET("oid", RastyUtils::getParamPOST("oid"));
		return $this->getComponentById("oid")->getPopulatedValue( $this->getMethod() );
	}
	
	public function fillEntity($entity){
		
		parent::fillEntity($entity);
		
		//$this->fillFromSaved( $this->getTurno() );
		
		$input = $this->getComponentById("backSuccess");
		$value = $input->getPopulatedValue( $this->getMethod() );
		$this->setBackToOnSuccess($value);
		
	}
	
	public function getType(){
		
		return "TurnoForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$this->fillFromSaved( $this->getTurno() );
		
		//Logger::log("cliente " . $this->getTurno()->getCliente()->getNombre());
		
		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("estadoTurnoEnSala", EstadoTurno::EnSala );
		
		$xtpl->assign("cancel", $this->getLinkCancel() );
		$xtpl->assign("lbl_cancel", $this->localize( $this->getLabelCancel() ) );
		
		$xtpl->assign("lbl_fecha", $this->localize("turno.fecha") );
		$xtpl->assign("lbl_hora", $this->localize("turno.hora") );
		$xtpl->assign("lbl_profesional", $this->localize("turno.profesional") );
		
		$xtpl->assign("lbl_cliente", $this->localize("turno.cliente") );
		$xtpl->assign("lbl_cliente_ayuda", $this->localize("turno.cliente.ayuda.autocomplete") );
		
		
		$xtpl->assign("lbl_obraSocial", $this->localize("turno.obraSocial") );
		$xtpl->assign("lbl_nroObraSocial", $this->localize("turno.nroObraSocial") );
		$xtpl->assign("lbl_estado", $this->localize("turno.estado") );
		$xtpl->assign("lbl_importe", $this->localize("turno.importe") );
		$xtpl->assign("lbl_prioridad", $this->localize("turno.prioridad") );
		$xtpl->assign("lbl_duracion", $this->localize("turno.duracion") );
		
		//si el cliente aún no fue registrado, mostramos el nombre y el teléfono indicados en el turno.
		$cliente = $this->getTurno()->getCliente();
		if( empty($cliente) ){

			$xtpl->assign("lbl_nombre", $this->localize("turno.nombre") );
			$xtpl->assign("lbl_telefono", $this->localize("turno.telefono") );
			
			$xtpl->assign("lbl_validar", $this->localize("turno.cliente.validar.msg") );
			$xtpl->assign("btn_validar", $this->localize("turno.cliente.validar") );
			
			$xtpl->parse("main.cliente_no_registrado" );
			
		}else{
			
			$xtpl->parse("main.cliente_registrado" );
			
		}
		
		
	}



	public function getLabelCancel()
	{
	    return $this->labelCancel;
	}

	public function setLabelCancel($labelCancel)
	{
	    $this->labelCancel = $labelCancel;
	}

	public function getEstados(){
		
		return TurnosUtils::localizeEntities(EstadoTurno::getItems());	
		
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
	
	public function getTurno()
	{
	    return $this->turno;
	}

	public function setTurno($turno)
	{
	    $this->turno = $turno;
	}
	
	public function getHoras(){
		
		return TurnosUtils::getHorasItems();	
		
	}
	
	public function getLinkCancel(){
		$params = array();
		
		$cliente = $this->getTurno()->getCliente();
		if( !empty( $cliente ) )
			$params["clienteOid"] = $this->getTurno()->getCliente()->getOid() ;			
			
		$profesional = $this->getTurno()->getProfesional();
		if( !empty( $profesional ) )
			$params["profesionalOid"] = $this->getTurno()->getProfesional()->getOid() ;			
			
		return LinkBuilder::getPageUrl( $this->getBackToOnCancel() , $params) ;
	}
	

	public function getFechaEditable()
	{
	    return $this->fechaEditable;
	}

	public function setFechaEditable($fechaEditable)
	{
	    $this->fechaEditable = $fechaEditable;
	}

	public function getHoraEditable()
	{
	    return $this->horaEditable;
	}

	public function setHoraEditable($horaEditable)
	{
	    $this->horaEditable = $horaEditable;
	}

	public function getIntervalo()
	{
	    return $this->intervalo;
	}

	public function setIntervalo($intervalo)
	{
	    $this->intervalo = $intervalo;
	}


	public function getEstadoUrgente()
	{
	    return $this->estadoUrgente;
	}

	public function setEstadoUrgente($estadoUrgente)
	{
	    $this->estadoUrgente = $estadoUrgente;
	}
	
	public function getPrioridades(){
		
		return TurnosUtils::localizeEntities(Prioridad::getItems());	
		
	}
	
	public function getDuraciones(){
		
		return TurnosUtils::getDuracionesTurno();	
		
	}
	
	public function setTurnoOid($turnoOid)
	{
		if(!empty($turnoOid) ){

			//a partir del id buscamos el turno.
			$turno = UIServiceFactory::getUITurnoService()->get($turnoOid);
		
			$this->setTurno($turno);
		}
	    
		
	}
	
}
?>