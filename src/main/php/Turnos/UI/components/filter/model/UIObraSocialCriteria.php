<?php
namespace Turnos\UI\components\filter\model;

use Turnos\UI\components\filter\model\UITurnosCriteria;

use Rasty\Grid\filter\model\UICriteria;
use Rasty\utils\RastyUtils;
use Turnos\Core\criteria\ObraSocialCriteria;

/**
 * Representa un criterio de búsqueda
 * para obras sociales
 * 
 * @author bernardo
 *
 */
class UIObraSocialCriteria extends UITurnosCriteria{


	private $nombre;
	
	private $codigo;
	
	
	public function __construct(){

		parent::__construct();

		
	}	
	
	protected function newCoreCriteria(){
		return new ObraSocialCriteria();
	}
	
	public function buildCoreCriteria(){
		
		$criteria = parent::buildCoreCriteria();
				
		$criteria->setNombre( $this->getNombre() );
		$criteria->setCodigo( $this->getCodigo() );
		
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