<?php

namespace Turnos\UI\components\form\turno;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\utils\TurnosUtils;
use Turnos\UI\service\finder\ProfesionalFinder;

use Turnos\Core\model\Turno;
use Turnos\Core\model\Prioridad;
use Turnos\Core\model\EstadoTurno;

use Rasty\Forms\form\Form;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\utils\LinkBuilder;

/**
 * Formulario para un turno (más rápido, menos datos)

 * @author bernardo
 * @since 19/03/2014
 */
class TurnoQuickForm extends Form{
		
			
	/**
	 * legend para el fieldset del turno.
	 * @var string
	 */
	private $turnoLegend;
	
	/**
	 * 
	 * @var Turno
	 */
	private $turno;
	
	public function __construct(){

		parent::__construct();

		//agregamos las propiedades a popular en el submit.
		//$this->setFechaEditable( true );
		//$this->setHoraEditable( true );
		//$this->setIntervalo(15);
		
		$this->addProperty("fecha");
		$this->addProperty("hora");
		$this->addProperty("profesional");
		$this->addProperty("nombre");
		$this->addProperty("telefono");
		$this->addProperty("duracion");
		
		$this->setLegend( $this->localize("turno.agregar.legend") );
		$this->setTurnoLegend( $this->localize("turno.agregar.turno_legend") );
		
		//inicializamos el turno.
		$turno = new Turno();
		$hora = new \DateTime();
		$hora->setTime(0,0,0);
		$turno->setHora( $hora );
		
		
		//la fecha es la de la agenda
		if( TurnosUtils::isFechaAgenda() )
			$turno->setFecha( TurnosUtils::getFechaAgenda() );
		else
			$turno->setFecha( new \DateTime() );

		//el profesional es el de la agenda
		if( TurnosUtils::isProfesionalAgenda() ){
			$turno->setProfesional( TurnosUtils::getProfesionalAgenda() );
		}	
		
		$turno->setImporte( 0 );
		
		$this->setTurno($turno);

		$this->setearDuracion();
		
	}
	
	public function getOid(){
		
		//return RastyUtils::getParamGET("oid", RastyUtils::getParamPOST("oid"));
		return $this->getComponentById("oid")->getPopulatedValue( $this->getMethod() );
	}
	
	public function fillEntity($entity){
		
		//tomar del get fecha,profesional,hora
		
		
		parent::fillEntity($entity);
		
		//seteamos datos default.
		$entity->setPrioridad(Prioridad::Normal);
		$entity->setImporte( 0 );
		$entity->setEstado(EstadoTurno::Asignado);
		
		//uppercase para el nombre del paciente
		$entity->setNombre( strtoupper( $entity->getNombre() ) );
		
	}
	
	public function getType(){
		
		return "TurnoQuickForm";
		
	}

	public function getOnClickCancel(){

		$popupDivId = $this->getPopupDivId();
		return "closeFinderPopup('#$popupDivId');";
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		$this->fillFromSaved( $this->getTurno() );

		//rellenamos el nombre con el texto inicial
		//$this->fillInput("nombre", $this->getInitialText() );
		
		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("lbl_fecha", $this->localize("turno.fecha") );
		$xtpl->assign("lbl_hora", $this->localize("turno.hora") );
		$xtpl->assign("lbl_profesional", $this->localize("turno.profesional") );
		$xtpl->assign("lbl_duracion", $this->localize("turno.duracion") );
		
		$xtpl->assign("lbl_nombre", $this->localize("turno.nombre") );
		$xtpl->assign("lbl_telefono", $this->localize("turno.telefono") );
		
		$xtpl->assign("lbl_cancel", $this->localize( "form.cancelar" ) );
		
		$xtpl->assign("turno_legend", $this->getTurnoLegend() );
		
		
	}
	

	public function getTurno()
	{
	    return $this->turno;
	}

	public function setTurno($turno)
	{
	    $this->turno = $turno;
	}
	
	/**
	 * parametro de la página para setear la hora del turno.
	 * @param string (H:i) $hora
	 */
	public function setHora($hora)
	{
		if( !empty($hora) ){
				$arrayHoraMinutos = explode(":", $hora);
				if( count($arrayHoraMinutos)>1 ){
				$time = new \DateTime();
				$time->setTime($arrayHoraMinutos[0],$arrayHoraMinutos[1],0);
				$this->getTurno()->setHora( $time );
				
				$this->setearDuracion();
				
			}
		}
	}
	/**
	 * parametro de la página para setear el oid profesional.
	 * @param string $oid
	 */
	public function setProfesionalOid($profesionalOid)
	{
		if(!empty($profesionalOid) ){

			//a partir del id buscamos el cliente.
			$profesional = UIServiceFactory::getUIProfesionalService()->get($profesionalOid);
		
			$this->getTurno()->setProfesional($profesional);
		}	    
		
	}
	
	/**
	 * parametro de la página para setear la fecha del turno.
	 * @param string (Y-m-d) $fecha
	 */
	public function setFecha($fecha){
		if( !empty($fecha) ){
				$arrayFecha = explode("-", $fecha);
				if( count($arrayFecha)>2 ){
					$f = new \DateTime();
					$f->setDate($arrayFecha[0],$arrayFecha[1],$arrayFecha[2]);
					$this->getTurno()->setFecha( $f );
					
					$this->setearDuracion();
				}
		}
	}

	public function getProfesionalFinderClazz(){
		
		return get_class( new ProfesionalFinder() );
		
	}
	
	public function setearDuracion(){
		
		//dada la fecha y hora buscamos cuánto sería la duración estándar del turno.
		
		$hora = $this->getTurno()->getHora();
		$horariosDelDia = UIServiceFactory::getUIHorarioService()->getHorariosDelDia($this->getTurno()->getFecha(), $this->getTurno()->getProfesional() );
		$duracionTurno = 15;
		foreach ($horariosDelDia as $horario) {
			
			if( $horario->incluyeHora( $hora ) )
				$duracionTurno = $horario->getDuracionTurno() ;
			
		}
		
		$this->getTurno()->setDuracion( $duracionTurno );
		
	}
	
	public function getDuraciones(){
		
		return TurnosUtils::getDuracionesTurno();	
		
	}
	

	public function getTurnoLegend()
	{
	    return $this->turnoLegend;
	}

	public function setTurnoLegend($turnoLegend)
	{
	    $this->turnoLegend = $turnoLegend;
	}
}
?>