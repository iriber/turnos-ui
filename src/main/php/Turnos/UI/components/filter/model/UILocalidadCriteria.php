<?php
namespace Turnos\UI\components\filter\model;


use Turnos\UI\components\filter\model\UITurnosCriteria;

use Rasty\Grid\filter\model\UICriteria;
use Rasty\utils\RastyUtils;
use Turnos\Core\criteria\LocalidadCriteria;

/**
 * Representa un criterio de búsqueda
 * para localidades
 * 
 * @author bernardo
 *
 */
class UILocalidadCriteria extends UITurnosCriteria{


	private $nombre;
	

	public function __construct(){

		parent::__construct();

		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		
	}	
	
	protected function newCoreCriteria(){
		return new LocalidadCriteria();
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