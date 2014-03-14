<?php
namespace Turnos\UI\components\filter\model;


use Turnos\UI\components\filter\model\UITurnosCriteria;

use Rasty\Grid\filter\model\UICriteria;
use Rasty\utils\RastyUtils;
use Turnos\Core\criteria\PracticaCriteria;

/**
 * Representa un criterio de búsqueda para prácticas.
 * 
 * @author bernardo
 * @since 09/03/2014
 *
 */
class UIPracticaCriteria extends UITurnosCriteria{


	private $fechaDesde;

	private $fechaHasta;
	
	private $cliente;
	
	private $profesional;
	
	private $obraSocial; 
	
	public function __construct(){

		parent::__construct();

		//$this->fillFromSaved();
	}
	
	

	public function getFechaDesde()
	{
	    return $this->fechaDesde;
	}

	public function setFechaDesde($fechaDesde)
	{
	    $this->fechaDesde = $fechaDesde;
	}

	public function getFechaHasta()
	{
	    return $this->fechaHasta;
	}

	public function setFechaHasta($fechaHasta)
	{
	    $this->fechaHasta = $fechaHasta;
	}
	
	protected function newCoreCriteria(){
		return new PracticaCriteria();
	}
	
	public function buildCoreCriteria(){
		
		$criteria = parent::buildCoreCriteria();
				
		$criteria->setFechaDesde( $this->getFechaDesde() );
		$criteria->setFechaHasta( $this->getFechaHasta() );
		$criteria->setCliente( $this->getCliente() );
		$criteria->setProfesional( $this->getProfesional() );
		$criteria->setObraSocial( $this->getObraSocial() );
		
		return $criteria;
	}
	

	public function getCliente()
	{
	    return $this->cliente;
	}

	public function setCliente($cliente)
	{
	    $this->cliente = $cliente;
	}

	public function getProfesional()
	{
	    return $this->profesional;
	}

	public function setProfesional($profesional)
	{
	    $this->profesional = $profesional;
	}

	public function getObraSocial()
	{
	    return $this->obraSocial;
	}

	public function setObraSocial($obraSocial)
	{
	    $this->obraSocial = $obraSocial;
	}
}
