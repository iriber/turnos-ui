<?php

namespace Turnos\UI\components\horarios;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;

use Rasty\utils\XTemplate;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\Horario;
use Turnos\Core\model\DiaSemana;

use Rasty\utils\LinkBuilder;

/**
 * Horarios de un profesional.
 * 
 * @author bernardo
 * @since 06/03/2014
 */
class HorariosProfesional extends RastyComponent{
		
	
	/**
	 * profesional del cual se listan los horarios
	 * @var Profesional
	 */
	private $profesional;

	
	public function __construct(){
		
		$this->setProfesional( new Profesional() ); 
	}
	
	public function getType(){
		
		return "HorariosProfesional";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$cantidad_ver = 1;
		
		if( $this->getProfesional() == null ){
			return;
		}
			
		$horarios = UIServiceFactory::getUIHorarioService()->getHorariosDelProfesional( $this->getProfesional() );
		
		//los agrupo por dia.
		
		
		$xtpl->assign("dia_label", $this->localize( "horario.dia" ) );
		$xtpl->assign("horaDesde_label",  $this->localize( "horario.horaDesde" ) );
		$xtpl->assign("horaHasta_label",  $this->localize( "horario.horaHasta" ) );
		$xtpl->assign("duracionTurno_label",  $this->localize( "horario.duracionTurno" ) );

		$xtpl->assign("borrar_label", $this->localize( "horario.borrar" ) );

		//$xtpl->assign("finalizarTurnoCallback",  $this->getFinalizarTurnoCallback() );
		
		$diaAnterior = -1;
		foreach ($horarios as $horario) {
			
			
			if( $diaAnterior>=0 && $diaAnterior!=$horario->getDia()){
				$xtpl->assign("dia",  $this->localize( DiaSemana::getLabel($diaAnterior) ) );
				$xtpl->parse("main.horario");	
			}
			
			//$xtpl->assign("turno_css", TurnosUtils::getEstadoTurnoCss($turno->getEstado()));	
			$xtpl->assign("horario_oid",  $horario->getOid() );
			$xtpl->assign("profesional_oid",  $horario->getProfesional()->getOid() );
			$xtpl->assign("horaDesde", TurnosUtils::formatTimeToView($horario->getHoraDesde(), "H:i" ) );
			$xtpl->assign("horaHasta", TurnosUtils::formatTimeToView($horario->getHoraHasta(), "H:i" ) );
			$xtpl->assign("duracionTurno", $horario->getDuracionTurno() );
			$xtpl->assign("linkBorrar",  LinkBuilder::getActionAjaxUrl( "BorrarHorario") );
			$xtpl->parse("main.horario.hora");
			
			$diaAnterior = $horario->getDia();
				
		}
		
		if( $diaAnterior>=0){
			$xtpl->assign("dia",  $this->localize( DiaSemana::getLabel($diaAnterior) ) );
			$xtpl->parse("main.horario");	
		}
			

	}
	
	

	public function setProfesionalOid($profesionalOid)
	{
		if(!empty($profesionalOid) ){

			//a partir del id buscamos el Profesional.
			$profesional = UIServiceFactory::getUIProfesionalService()->get($profesionalOid);
		
			$this->setProfesional($profesional);
		}
	    
		
	}

	protected function initObserverEventType(){

		$this->addEventType( "Profesional" );
		$this->addEventType( "Horario" );
	}

	public function getProfesional()
	{
	    return $this->profesional;
	}

	public function setProfesional($profesional)
	{
	    $this->profesional = $profesional;
	}
	
	
}
?>