<?php
namespace Turnos\UI\pages;

use Rasty\components\RastyPage;
use Rasty\utils\LinkBuilder;

/**
 * página genérica para la app de turnos
 * 
 * @author bernardo
 * @since 15/08/2013
 */
abstract class TurnosPage extends RastyPage{


	
	public function getTitle(){
		return $this->localize( "turnos_app.title" );
	}

	public function getMenuGroups(){

		return array();
	}

	public function getLinkTurnos(){
		
		return LinkBuilder::getPageUrl( "Turnos") ;
		
	}
	
	public function getLinkActionAgregarTurno(){
		
		return LinkBuilder::getActionUrl( "AgregarTurno") ;
		
	}

	public function getLinkActionModificarTurno(){
		
		return LinkBuilder::getActionUrl( "ModificarTurno") ;
		
	}

	public function getLinkClientes(){
		
		return LinkBuilder::getPageUrl( "Clientes") ;
		
	}
		
	public function getLinkActionAgregarCliente(){
		
		return LinkBuilder::getActionUrl( "AgregarCliente") ;
		
	}

	public function getLinkActionModificarCliente(){
		
		return LinkBuilder::getActionUrl( "ModificarCliente") ;
		
	}
	
	public function getLinkHistoriaClinica(){
		
		return LinkBuilder::getPageUrl( "HistoriaClinica") ;
		
	}
	
	public function getLinkActionAgregarPractica(){
		
		return LinkBuilder::getActionUrl( "AgregarPractica") ;
		
	}
	
	public function getLinkActionModificarPractica(){
		
		return LinkBuilder::getActionUrl( "ModificarPractica") ;
		
	}
	
	public function getLinkActionAgregarAusencia(){
		
		return LinkBuilder::getActionUrl( "AgregarAusencia") ;
		
	}
	
	public function getLinkActionAgregarNomenclador(){
		
		return LinkBuilder::getActionUrl( "AgregarNomenclador") ;
		
	}
	
	public function getLinkActionModificarNomenclador(){
		
		return LinkBuilder::getActionUrl( "ModificarNomenclador") ;
		
	}
	
	public function getLinkActionAgregarObraSocial(){
		
		return LinkBuilder::getActionUrl( "AgregarObraSocial") ;
		
	}
	
	public function getLinkActionModificarObraSocial(){
		
		return LinkBuilder::getActionUrl( "ModificarObraSocial") ;
		
	}
	
	public function getLinkActionAgregarHorario(){
		
		return LinkBuilder::getActionUrl( "AgregarHorario") ;
		
	}
}
?>