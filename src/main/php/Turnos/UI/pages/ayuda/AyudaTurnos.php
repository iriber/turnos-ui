<?php
namespace Turnos\UI\pages\ayuda;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\components\RastyPage;
use Rasty\security\RastySecurityContext;

use Rasty\utils\LinkBuilder;

class AyudaTurnos extends TurnosPage{

	
	public function __construct(){
		
	}
	
	public function getTitle(){
		return $this->localize( "ayuda.turnos.titulo" );
	}

	
	public function getType(){
		
		return "AyudaTurnos";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){

		$user = RastySecurityContext::getUser();
		
		$xtpl->assign("usuario", $user->getName());
		
		$xtpl->assign("ayuda_turnos_titulo", $this->localize( "ayuda.turnos.titulo" ));
		
		$xtpl->assign("volver_indice_label", $this->localize( "ayuda.agenda.volver.indice.label" ) );
		$xtpl->assign("cerrar_ayuda_label", $this->localize( "ayuda.cerrar.label" ) );
		
		$xtpl->assign("linkAyudaTurnos", LinkBuilder::getPageUrl( "AyudaTurnos") );
		
		
		//items para panel de trabajo
		if( TurnosUtils::isProfesionalLogged() ){
			$items = array();
			$items[] = $this->buildItem( "ayuda.panel.iconos.titulo" , "AyudaIconosPanel" );
			$items[] = $this->buildItem( "ayuda.turno.estados.titulo" , "AyudaEstadoTurno" ); 
			$items[] = $this->buildItem( "ayuda.agenda.colores.titulo" , "AyudaColoresAgenda" );
			
			$contenido = array ( "titulo" =>  $this->localize( "ayuda.panelTrabajo.header" ), "items" =>$items, "img" =>$this->getWebPath() . "css/images/profesional_home_48.png" );
			$this->parseContenido($xtpl, $contenido);
		}
		
		//items para agenda de turnos
		$items = array();
		$items[] = $this->buildItem( "ayuda.agenda.agregarTurno.titulo" , "AyudaAgregarTurno" );			
		$items[] = $this->buildItem( "ayuda.agenda.agregarSobreturno.titulo" , "AyudaAgregarSobreturno" );			
		$items[] = $this->buildItem( "ayuda.agenda.pacienteEnSala.titulo" , "AyudaPacienteEnSala" );			
		$items[] = $this->buildItem( "ayuda.turno.estados.titulo" , "AyudaEstadoTurno" ); 
		$items[] = $this->buildItem( "ayuda.agenda.colores.titulo" , "AyudaColoresAgenda" ); 
		$items[] = $this->buildItem( "ayuda.agenda.iconos.titulo" , "AyudaIconosAgenda" );
		
		$contenido = array ( "titulo" =>  $this->localize( "ayuda.agenda.header" ), "items" =>$items, "img" =>$this->getWebPath() . "css/images/turnos_48.png" );
		$this->parseContenido($xtpl, $contenido);
		
		
		
	}
	
	protected function parseContenido(XTemplate $xtpl, $contenido){
		
		$xtpl->assign("ayuda_item_titulo", $contenido["titulo"] );
		$xtpl->assign("ayuda_item_img", $contenido["img"] );
		
		foreach ($contenido["items"] as $item) {
			
			$xtpl->assign("linkItemAyuda", $item["link"] );
			$xtpl->assign("ayuda_subitem_titulo", $item["titulo"] );
			$xtpl->parse("main.ayuda_item.ayuda_subitem" );
		}			
		
		$xtpl->parse("main.ayuda_item" );
	}

	protected function buildItem($msg, $componentType){
		
		return array(
							"titulo" => $this->localize( $msg ),
							"link" => LinkBuilder::getComponentUrl( $componentType ) 
					);
	}
}
?>