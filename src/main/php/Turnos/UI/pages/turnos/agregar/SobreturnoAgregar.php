<?php
namespace Turnos\UI\pages\turnos\agregar;


class SobreturnoAgregar extends TurnoAgregar{

	public function getTitle(){
		return $this->localize( "turno.agregar_sobreturno.title" );
	}

	public function getType(){
		
		return "SobreturnoAgregar";
		
	}	

}
?>