<?php
namespace Turnos\UI\components\filter\model;


use Turnos\UI\components\filter\model\UITurnosCriteria;

use Rasty\Grid\filter\model\UICriteria;
use Rasty\utils\RastyUtils;
use Turnos\Core\criteria\PlanObraSocialCriteria;
use Turnos\Core\model\ObraSocial;

/**
 * Representa un criterio de búsqueda
 * para planes de obras sociales
 * 
 * @author bernardo
 * @since 24/04/2014
 *
 */
class UIPlanObraSocialCriteria extends UITurnosCriteria{


	private $nombre;
	
	private $obraSocial;


	public function __construct(){

		parent::__construct();

		//$this->fillFromSaved();
	}
	
	public function getNombre()
	{
	    return $this->nombre;
	}

	public function setNombre($nombre)
	{
	    $this->nombre = $nombre;
	}

	protected function newCoreCriteria(){
		return new PlanObraSocialCriteria();
	}
	
	public function buildCoreCriteria(){
		
		$criteria = parent::buildCoreCriteria();
				
		$criteria->setNombre( $this->getNombre() );
		$criteria->setObraSocial( $this->getObraSocial() );
		
		return $criteria;
	}

	public function getObraSocial()
	{
	    return $this->obraSocial;
	}

	public function setObraSocial($obraSocial)
	{
	    $this->obraSocialNombre = $obraSocial;
	}

}