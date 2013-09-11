<?php

namespace Turnos\UI\components\form\horario;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\finder\ProfesionalFinder;

use Turnos\UI\service\UIServiceFactory;

use Rasty\Forms\form\Form;
use Turnos\UI\service\finder\ObraSocialFinder;
use Turnos\UI\service\finder\LocalidadFinder;

use Rasty\components\RastyComponent;
use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\utils\LinkBuilder;

use Turnos\Core\model\Ausencia;

/**
 * Formulario para Ausencia

 * @author bernardo
 * @since 29/08/2013
 */
class AusenciaForm extends Form{
		
	
	/**
	 * label para el cancel
	 * @var string
	 */
	private $labelCancel;
	
	private $ausencia;
	

	public function __construct(){

		parent::__construct();
		$this->setLabelCancel("form.cancelar");

		$horaDesde = new \DateTime();
		$horaDesde->setTime(8, 0);
		
		$horaHasta = new \DateTime();
		$horaHasta->setTime(12, 0);
		
		$this->ausencia = new Ausencia();
		$this->ausencia->setHoraDesde($horaDesde);
		$this->ausencia->setHoraHasta($horaHasta);
		
		//agregamos las propiedades a popular en el submit.
		$this->addProperty("fechaDesde");
		$this->addProperty("fechaHasta");
		$this->addProperty("horaDesde");
		$this->addProperty("horaHasta");
		$this->addProperty("observaciones");
		$this->addProperty("profesional");
		
		$this->setBackToOnSuccess("AusenciaAgregar");
		$this->setBackToOnCancel("TurnosHome");

		
	}
	
	public function fillEntity($entity){
		
		parent::fillEntity($entity);

		if( strtoupper($this->getMethod())=="POST" ){

			$tipoAusencia = RastyUtils::getParamPOST( "tipoAusencia" );
				
		}else{
			
			$tipoAusencia = RastyUtils::getParamGET( "tipoAusencia" );
		}
		
		switch ($tipoAusencia) {
			case 1:
				$entity->setFechaHasta( null );
				$entity->setHoraHasta( null );
				$entity->setHoraDesde( null );
			;
			break;
			
			case 2:
				$entity->setFechaHasta( null );
			;
			break;
			
			case 3:
				$entity->setHoraHasta( null );
				$entity->setHoraDesde( null );
			;
			break;
			
			case 4:
			;
			break;
			
			default:
				;
			break;
		}
		
		/*
		$profesionalOid = $this->getComponentById("profesionalOid")->getPopulatedValue( $this->getMethod() );
		$this->setProfesionalOid($profesionalOid);
		$entity->setProfesional( $this->getAusencia()->getProfesional());
		*/
		
		$input = $this->getComponentById("backSuccess");
		$value = $input->getPopulatedValue( $this->getMethod() );
		$this->setBackToOnSuccess($value);
		
	}
	
	public function getType(){
		
		return "AusenciaForm";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		//$this->fillFromSaved();
		
		parent::parseXTemplate($xtpl);
		
		$xtpl->assign("cancel", $this->getLinkCancel() );
		$xtpl->assign("lbl_cancel", $this->localize( $this->getLabelCancel() ) );
		
		
		$xtpl->assign("ausencia_porFecha", $this->localize("ausencia.porFecha") );
		$xtpl->assign("ausencia_porFecha_ejemplo", $this->localize("ausencia.porFecha.ejemplo") );

		$xtpl->assign("ausencia_porFechaHorario", $this->localize("ausencia.porFechaHorario") );
		$xtpl->assign("ausencia_porFechaHorario_ejemplo", $this->localize("ausencia.porFechaHorario.ejemplo") );

		$xtpl->assign("ausencia_porRangoFecha", $this->localize("ausencia.porRangoFecha") );
		$xtpl->assign("ausencia_porRangoFecha_ejemplo", $this->localize("ausencia.porRangoFecha.ejemplo") );
		
		$xtpl->assign("ausencia_porRangoFechaHorario", $this->localize("ausencia.porRangoFechaHorario") );
		$xtpl->assign("ausencia_porRangoFechaHorario_ejemplo", $this->localize("ausencia.porRangoFechaHorario.ejemplo") );
		
		$xtpl->assign("ausencia_agregar_legend", $this->localize("ausencia.agregar.legend") );
		$xtpl->assign("ausencia_agregar_legend_tipo", $this->localize("ausencia.agregar.legend.tipo") );
		
		$this->parseAusenciasVigentes($xtpl);
		
		$xtpl->assign("lbl_profesional", $this->localize( "ausencia.profesional" ) );
		$xtpl->assign("lbl_observaciones", $this->localize( "ausencia.observaciones" ) );
		$xtpl->assign("lbl_fechaDesde", $this->localize( "ausencia.fechaDesde" ) );
		$xtpl->assign("lbl_fechaHasta", $this->localize( "ausencia.fechaHasta" ) );
		$xtpl->assign("lbl_horaDesde", $this->localize( "ausencia.horaDesde" ) );
		$xtpl->assign("lbl_horaHasta", $this->localize( "ausencia.horaHasta" ) );
		
		
		
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
		
		return LinkBuilder::getPageUrl( $this->getBackToOnCancel() , $params) ;
	}

	public function setProfesionalOid($profesionalOid)
	{
	    //a partir del id buscamos el profesional
		$profesional = UIServiceFactory::getUIProfesionalService()->get($profesionalOid);
		
		$this->ausencia->setProfesional($profesional);
	}
	

	public function getAusencia()
	{
	    return $this->ausencia;
	}

	public function setAusencia($ausencia)
	{
	    $this->ausencia = $ausencia;
	}
	
	public function getProfesionalFinderClazz(){
		
		return get_class( new ProfesionalFinder() );
		
	}
	
	public function parseAusenciasVigentes(XTemplate $xtpl){
		
		$hoy = new \DateTime();
		//$hoy->setDate( date("Y"), date("m"), date("d"));
		$ausencias = UIServiceFactory::getUIAusenciaService()->getAusenciasVigentes($hoy, $this->getAusencia()->getProfesional() );
		
		$xtpl->assign("ausencias_vigentes_msg", $this->localize("ausencias.vigentes.msg") );
		$xtpl->assign("lbl_borrar", $this->localize( "ausencia.borrar" ) );
		$xtpl->assign("linkBorrar",  LinkBuilder::getActionAjaxUrl( "BorrarAusencia") );
		
		foreach ($ausencias as $ausencia) {
			
			$xtpl->assign("vigente_observaciones", $ausencia->getObservaciones() );
			$xtpl->assign("vigente_oid", $ausencia->getOid() );
			$xtpl->assign("vigente_profesionalOid", $ausencia->getProfesional()->getOid() );
			
			$mensaje = TurnosUtils::getMensajeAusencia($ausencia);
			$xtpl->assign("vigente_mensaje", $mensaje );
			
			$xtpl->parse("main.ausencias_vigentes.ausencia");
		}
		
		if( count($ausencias) > 0 )
			$xtpl->parse("main.ausencias_vigentes");
			
		
		
	}

}
?>