<?php

namespace Turnos\UI\components\form\turno;

use Turnos\UI\service\finder\NomencladorFinder;

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
use Turnos\Core\model\ClienteObraSocial;
use Turnos\Core\model\Profesional;
use Turnos\Core\model\Prioridad;
use Turnos\Core\model\TipoAfiliadoObraSocial;

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
	
	private $clienteObraSocial;
	
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
		$this->addProperty("importe");
		$this->addProperty("estado");
		$this->addProperty("prioridad");
		$this->addProperty("duracion");
		$this->addProperty("telefono");
		$this->addProperty("nombre");
		$this->addProperty("nomenclador");
		$this->addProperty("observaciones");

		$this->addProperty("obraSocial", "clienteObraSocial");
		$this->addProperty("nroObraSocial", "clienteObraSocial");
		$this->addProperty("tipoAfiliado", "clienteObraSocial");
		//$this->addProperty("planObraSocial", "clienteObraSocial");
		
		$this->setBackToOnSuccess("TurnosHome");
		$this->setBackToOnCancel("TurnosHome");	
		
		$this->clienteObraSocial = new ClienteObraSocial();
		
		//$this->setTurnoOid( RastyUtils::getParamGET("turnoOid",0));

		
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

		//buscamos el plan de obra social (está en un combo asi que tenemos el oid)
		
		$planOid = $this->getComponentById("planObraSocial")->getPopulatedValue( $this->getMethod() );
		if(!empty($planOid)){
			$this->clienteObraSocial->setPlanObraSocial( UIServiceFactory::getUIPlanObraSocialService()->get($planOid) );
		}
		
		$this->fillRelatedEntity("clienteObraSocial", $this->clienteObraSocial );
		
		$this->clienteObraSocial->setCliente($entity->getCliente());
		$entity->setClienteObraSocial($this->clienteObraSocial);
		
		
		//uppercase para el nombre del paciente
		$entity->setNombre( strtoupper( $entity->getNombre() ) );
		$entity->setObservaciones( strtoupper( $entity->getObservaciones() ) );
	}
	
	public function getType(){
		
		return "TurnoForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$this->fillFromSaved( $this->getTurno() );
		
		//Logger::log("cliente " . $this->getTurno()->getCliente()->getNombre());
		
		parent::parseXTemplate($xtpl);
		
		//$xtpl->assign("estadoTurnoEnSala", EstadoTurno::EnSala );
		//si la fecha es la de hoy, dejamos el paciente en sala.
		$esHoy = TurnosUtils::isSameDay( $this->getTurno()->getFecha() , new \DateTime());
		if( $esHoy )
			$xtpl->assign("estadoTurnoDefault", EstadoTurno::EnSala );
		else
			$xtpl->assign("estadoTurnoDefault", EstadoTurno::Asignado );
		
		$xtpl->assign("quirofanoOid", TurnosUtils::getQuirofanoOid() );
		
		$plan = $this->getTurno()->getPlanObraSocial();
		if($plan!=null)
			$xtpl->assign("planObraSocialOid", $plan->getOid() );
				
		$xtpl->assign("cancel", $this->getLinkCancel() );
		$xtpl->assign("lbl_cancel", $this->localize( $this->getLabelCancel() ) );
		
		$xtpl->assign("lbl_fecha", $this->localize("turno.fecha") );
		$xtpl->assign("lbl_hora", $this->localize("turno.hora") );
		$xtpl->assign("lbl_profesional", $this->localize("turno.profesional") );
		
		$xtpl->assign("lbl_cliente", $this->localize("turno.cliente") );
		$xtpl->assign("lbl_cliente_ayuda", $this->localize("turno.cliente.ayuda.autocomplete") );
		
		$xtpl->assign("lbl_observaciones", $this->localize("turno.observaciones") );
		
		$xtpl->assign("lbl_obraSocial", $this->localize("turno.obraSocial") );
		$xtpl->assign("lbl_nroObraSocial", $this->localize("turno.nroObraSocial") );
		$xtpl->assign("lbl_tipoAfiliado", $this->localize("turno.tipoAfiliado") );
		$xtpl->assign("lbl_planObraSocial", $this->localize("turno.planObraSocial") );
		$xtpl->assign("buscar_obraSocial_title", $this->localize("turno.buscarClienteObraSocial.title") );
		
		$xtpl->assign("lbl_estado", $this->localize("turno.estado") );
		$xtpl->assign("lbl_importe", $this->localize("turno.importe") );
		$xtpl->assign("lbl_prioridad", $this->localize("turno.prioridad") );
		$xtpl->assign("lbl_duracion", $this->localize("turno.duracion") );
		$xtpl->assign("lbl_nomenclador", $this->localize("turno.nomenclador") );
		
		$xtpl->assign("lbl_profesionalOpera", $this->localize("turno.profesionalOpera") );
		
		
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
		//$xtpl->assign("lbl_verObrasSociales", $this->localize("turno.clienteObraSocial.buscar.msg") );
		$xtpl->assign("btn_verObrasSociales", $this->localize("turno.clienteObraSocial.buscar") );
		$xtpl->assign("lbl_agregar_cliente", $this->localize( "turno.cliente.agregar" ) );
		$xtpl->assign("buscar_cliente_title", $this->localize( "turno.cliente.validar.buscar.title" ) );
		
		
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
	    
	    $this->getClienteObraSocial()->setObraSocial($turno->getObraSocial());
		$this->getClienteObraSocial()->setNroObraSocial($turno->getNroObraSocial());
		$this->getClienteObraSocial()->setPlanObraSocial($turno->getPlanObraSocial());
		$this->getClienteObraSocial()->setTipoAfiliado($turno->getTipoAfiliado());
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

	public function getNomencladorFinderClazz(){
		
		return get_class( new NomencladorFinder() );
		
	}	
	
	public function getTiposAfiliado(){
		
		$tipos[-1] = $this->localize("tipoAfiliado.elegir");
		
		$tipos = array_merge($tipos, TurnosUtils::localizeEntities(TipoAfiliadoObraSocial::getItems()));
		
		return $tipos;
		
	}
	
	public function getPlanesObraSocial(){
		
		$os = $this->getTurno()->getObraSocial();
		
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
	
	
	/**
	 * redefinimos para llenar clienteObraSocial
	 */
	public function fillFromSaved($entity=null){
	
		//$page = RastyUtils::getParamSESSION("page",1);
    	//$this->setPage($page);

		parent::fillFromSaved($entity);
		//Logger::log("begin fillFromSaved");
		
		$properties = array("obraSocial", "nroObraSocial", "tipoAfiliado");
		
		foreach ($properties as $property) {
			
			$value = $this->getSavedProperty($property);

			if(!empty($value )){
				
				$input = $this->getComponentById($property);
				$value = $input->formatValue($value);
				$input->setValue($value);	
				
				if(!empty($this->clienteObraSocial))
					ReflectionUtils::doSetter( $this->clienteObraSocial, $property, $value );
			}
			
		}
	}
	
}
?>