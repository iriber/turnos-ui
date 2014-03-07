<?php

namespace Turnos\UI\components\form\horario;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\finder\ProfesionalFinder;

use Turnos\UI\service\UIServiceFactory;

use Rasty\Forms\form\Form;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\utils\LinkBuilder;

use Turnos\Core\model\Horario;
use Turnos\Core\model\DiaSemana;

/**
 * Formulario para Horario

 * @author bernardo
 * @since 05/03/2014
 */
class HorarioForm extends Form{
		
	
	/**
	 * label para el cancel
	 * @var string
	 */
	private $labelCancel;
	
	private $horario;
	

	public function __construct(){

		parent::__construct();
		$this->setLabelCancel("form.cancelar");

		$horaDesde = new \DateTime();
		$horaDesde->setTime(8, 0);
		
		$horaHasta = new \DateTime();
		$horaHasta->setTime(12, 0);
		
		$this->horario = new Horario();
		
		//agregamos las propiedades a popular en el submit.
		$this->addProperty("dia");
		$this->addProperty("horaDesde");
		$this->addProperty("horaHasta");
		$this->addProperty("duracionTurno");
		$this->addProperty("profesional");
		
		$this->setBackToOnSuccess("HorariosProfesional");
		$this->setBackToOnCancel("HorariosProfesional");

	}
	
	public function getAction(){
		
		return $this->getLinkActionAgregarHorario();		
	}
	
	public function fillEntity($entity){
		
		parent::fillEntity($entity);

	
		$input = $this->getComponentById("backSuccess");
		$value = $input->getPopulatedValue( $this->getMethod() );
		$this->setBackToOnSuccess($value);
		
	}
	
	public function getType(){
		
		return "HorarioForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		//$this->fillFromSaved();
		
		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("cancel", $this->getLinkCancel() );
		$xtpl->assign("lbl_cancel", $this->localize( $this->getLabelCancel() ) );
		
		$xtpl->assign("horario_agregar_legend", $this->localize("horario.agregar.legend") );
		
		//$this->parseHorariosVigentes($xtpl);
		
		$xtpl->assign("lbl_profesional", $this->localize( "horario.profesional" ) );
		$xtpl->assign("lbl_horaDesde", $this->localize( "horario.horaDesde" ) );
		$xtpl->assign("lbl_horaHasta", $this->localize( "horario.horaHasta" ) );
		$xtpl->assign("lbl_dia", $this->localize( "horario.dia" ) );
		$xtpl->assign("lbl_duracionTurno", $this->localize( "horario.duracionTurno" ) );
		
	}

	public function getLabelCancel()
	{
	    return $this->labelCancel;
	}

	public function setLabelCancel($labelCancel)
	{
	    $this->labelCancel = $labelCancel;
	}

	public function getLinkCancel(){
		$params = array();
		if( $this->getHorario()->getProfesional()!=null)
			$params["profesionalOid"] = $this->getHorario()->getProfesional()->getOid();
		return LinkBuilder::getPageUrl( $this->getBackToOnCancel() , $params) ;
	}

	public function setProfesionalOid($profesionalOid)
	{
	    //a partir del id buscamos el profesional
		$profesional = UIServiceFactory::getUIProfesionalService()->get($profesionalOid);
		
		$this->getHorario()->setProfesional($profesional);
	}
	

	
	public function getProfesionalFinderClazz(){
		
		return get_class( new ProfesionalFinder() );
		
	}
	

	public function getHorario()
	{
	    return $this->horario;
	}

	public function setHorario($horario)
	{
	    $this->horario = $horario;
	}
	
	public function getDuraciones(){
		
		return TurnosUtils::getDuracionesTurno();	
		
	}

	public function getDias(){
		
		return TurnosUtils::localizeEntities(DiaSemana::getItems());		
		
	}
	
	protected function initObserverEventType(){

		$this->addEventType( "Profesional" );
		//$this->addEventType( "Especialidad" );
	}
	
	public function getLinkActionAgregarHorario(){
		
		return LinkBuilder::getActionUrl( "AgregarHorario") ;
		
	}

	
}
?>