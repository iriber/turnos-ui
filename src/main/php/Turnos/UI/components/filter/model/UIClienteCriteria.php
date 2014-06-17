<?php
namespace Turnos\UI\components\filter\model;


use Turnos\UI\components\filter\model\UITurnosCriteria;

use Rasty\Grid\filter\model\UICriteria;
use Rasty\utils\RastyUtils;
use Turnos\Core\criteria\ClienteCriteria;

/**
 * Representa un criterio de b�squeda.
 * 
 * @author bernardo
 *
 */
class UIClienteCriteria extends UITurnosCriteria{


	private $nombre;
	
	private $nombreEqual;
	
	private $nroHistoriaClinica;
	
	private $nroObraSocial;
	
	private $nroDocumento;
	
	private $obraSocialNombre;

	private $domicilio;

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

	public function getNroHistoriaClinica()
	{
	    return $this->nroHistoriaClinica;
	}

	public function setNroHistoriaClinica($nroHistoriaClinica)
	{
	    $this->nroHistoriaClinica = $nroHistoriaClinica;
	}
	
	
	protected function newCoreCriteria(){
		return new ClienteCriteria();
	}
	
	public function buildCoreCriteria(){
		
		$criteria = parent::buildCoreCriteria();
				
		$criteria->setNombre( $this->getNombre() );
		$criteria->setNombreEqual( $this->getNombreEqual() );
		$criteria->setNroHistoriaClinica( $this->getNroHistoriaClinica() );
		$criteria->setNroObraSocial( $this->getNroObraSocial() );
		$criteria->setObraSocialNombre( $this->getObraSocialNombre() );
		$criteria->setDomicilio( $this->getDomicilio() );
		$criteria->setNroDocumento( $this->getNroDocumento() );
		
		return $criteria;
	}

	public function getNroObraSocial()
	{
	    return $this->nroObraSocial;
	}

	public function setNroObraSocial($nroObraSocial)
	{
	    $this->nroObraSocial = $nroObraSocial;
	}

	public function getObraSocialNombre()
	{
	    return $this->obraSocialNombre;
	}

	public function setObraSocialNombre($obraSocialNombre)
	{
	    $this->obraSocialNombre = $obraSocialNombre;
	}

	public function getDomicilio()
	{
	    return $this->domicilio;
	}

	public function setDomicilio($domicilio)
	{
	    $this->domicilio = $domicilio;
	}

	public function getNroDocumento()
	{
	    return $this->nroDocumento;
	}

	public function setNroDocumento($nroDocumento)
	{
	    $this->nroDocumento = $nroDocumento;
	}

	public function getNombreEqual()
	{
	    return $this->nombreEqual;
	}

	public function setNombreEqual($nombreEqual)
	{
	    $this->nombreEqual = $nombreEqual;
	}
}
