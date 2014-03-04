<?php
namespace Turnos\UI\components\filter\model;


use Turnos\UI\components\filter\model\UITurnosCriteria;

use Rasty\Grid\filter\model\UICriteria;
use Rasty\utils\RastyUtils;
use Turnos\Core\criteria\EspecialidadCriteria;

/**
 * Representa un criterio de búsqueda
 * para Especialidades
 * 
 * @author bernardo
 *
 */
class UIEspecialidadCriteria extends UITurnosCriteria{


	private $nombre;
	

	public function __construct(){

		parent::__construct();

	}	
	
	protected function newCoreCriteria(){
		return new EspecialidadCriteria();
	}
	
	public function buildCoreCriteria(){
		
		$criteria = parent::buildCoreCriteria();
				
		$criteria->setNombre( $this->getNombre() );
		
		return $criteria;
	}



	public function getNombre()
	{
	    return $this->nombre;
	}

	public function setNombre($nombre)
	{
	    $this->nombre = $nombre;
	}

}