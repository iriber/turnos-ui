<?php
namespace Turnos\UI\components\filter\model;


use Turnos\UI\components\filter\model\UITurnosCriteria;

use Rasty\Grid\filter\model\UICriteria;
use Rasty\utils\RastyUtils;
use Turnos\Core\criteria\NomencladorCriteria;

/**
 * Representa un criterio de búsqueda
 * para Nomenclador
 * 
 * @author bernardo
 *
 */
class UINomencladorCriteria extends UITurnosCriteria{

	private $nombre;
	
	private $codigo;
	
	
	public function __construct(){

		parent::__construct();

	}	
	
	protected function newCoreCriteria(){
		return new NomencladorCriteria();
	}
	
	public function buildCoreCriteria(){
		
		$criteria = parent::buildCoreCriteria();
				
		$criteria->setCodigo( $this->getCodigo() );
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


	public function getCodigo()
	{
	    return $this->codigo;
	}

	public function setCodigo($codigo)
	{
	    $this->codigo = $codigo;
	}
}