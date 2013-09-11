<?php
namespace Turnos\UI\pages\turnos;


use Turnos\UI\pages\TurnosPage;

use Turnos\UI\components\filter\model\UIProfesionalCriteria;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\service\UIProfesionalService;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\i18n\Locale;
use Rasty\utils\LinkBuilder;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;

use Rasty\Grid\filter\model\UICriteria;

use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

class TurnosHome extends TurnosPage{

	
	public function getTitle(){
		return $this->localize( "turnos_home.title" );
	}

	public function getMenuGroups(){

		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "turno.agregar_sobreturno" ) );
		$menuOption->setPageName("SobreturnoAgregar");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/turnos_48.png" );
		$menuGroup->addMenuOption( $menuOption );

		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "ausencia.agregar" ) );
		$menuOption->setPageName("AusenciaAgregar");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/ausencias_48.png" );
		$menuGroup->addMenuOption( $menuOption );
					
		return array($menuGroup);
	}
		
	public function getType(){
		
		return "TurnosHome";
		
	}	

	public function getProfesional(){
	
		$profesional = new Profesional();
				
		if( TurnosUtils::isProfesionalAgenda() ){
			$profesional = TurnosUtils::getProfesionalAgenda();
		}else{
			
			$profesional->setOid(1);
		}
		return $profesional;
	}
	
	public function getFecha(){
	
		if(TurnosUtils::isFechaAgenda() ){
			$fecha = TurnosUtils::getFechaAgenda(); 
		}else{
			$fecha = new \DateTime(date("Ymd")) ;
		}
		return $fecha;
	}
	
	protected function parseXTemplate(XTemplate $xtpl){
		
		//obtenemos el profesional logueado si es que hay uno.
		$profesional = $this->getProfesional();
		//obtenemos la fecha seleccionada.
		$fecha = $this->getFecha();
		
		//parseamos el combo de profesionales.
		$this->parseProfesionales( $xtpl, $profesional);
		
		
		//ayuda
		$xtpl->assign("ayuda_label", $this->localize( "ayuda" ) );
		$xtpl->assign("ayuda_agenda_msg", $this->localize( "ayuda.agenda.titulo" ) );
		$xtpl->assign("linkAyudaAgenda", LinkBuilder::getPageUrl( "AyudaTurnos") );
		
		
		//parseamos la fecha.		
		$xtpl->assign("fechaDia", TurnosUtils::dayOfDate($fecha) );
		$xtpl->assign("fechaMes", TurnosUtils::monthOfDate($fecha) -1 );
		$xtpl->assign("fechaAnio", TurnosUtils::yearOfDate($fecha) );
		$xtpl->assign("fecha", TurnosUtils::formatDateToView($fecha) );

		//parseamos los títulos de los boxes.
		$xtpl->assign("agenda_subtitle", $this->localize( "turnos_home.agenda" ) );
		$xtpl->assign("calendario_subtitle", $this->localize( "turnos_home.calendario" ) );
		$xtpl->assign("profesional_subtitle", $this->localize( "turnos_home.profesional" ) );
		
		//parseamos los labels de totales.
		$xtpl->assign("totales_subtitle", $this->localize( "turnos_home.totales" ) );
		$xtpl->assign("caja_label", $this->localize( "turnos_home.en_caja" ) );
		
		$xtpl->assign("ensala_label", EstadoTurno::getLabel(EstadoTurno::EnSala) );
		$xtpl->assign("asignados_label", EstadoTurno::getLabel(EstadoTurno::Asignado) );
		$xtpl->assign("atendidos_label", EstadoTurno::getLabel(EstadoTurno::Atendido) );
		
		$xtpl->assign("estadoTurnoEnCurso", EstadoTurno::EnCurso );
		$xtpl->assign("linkHistoriaClinica",  LinkBuilder::getPageUrl( "HistoriaClinica" ) );
		
		/* 
		$xtpl->assign("ensala_label", EstadoTurno::getLabel(EstadoTurno::EnSala) );
		$xtpl->assign("asignados_label", EstadoTurno::getLabel(EstadoTurno::Asignado) );
		$xtpl->assign("atendidos_label", EstadoTurno::getLabel(EstadoTurno::Atendido) );
		*/
	}
	
	protected function parseProfesionales(XTemplate $xtpl, Profesional $selected){
	
		$service = UIServiceFactory::getUIProfesionalService();

		$criteria = new UIProfesionalCriteria();
		$criteria->addOrder("nombre", UICriteria::TYPE_ASC);
		$profesionales = $service->getList($criteria);

        foreach ($profesionales as $profesional) {

            $xtpl->assign('label', $profesional->__toString() );
            $xtpl->assign('value', RastyUtils::selected($profesional->getOid(), $selected->getOid()));

            $xtpl->parse('main.profesional_option');
        }
	}

}
?>