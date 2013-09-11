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
	
	private $nroHistoriaClinica;
	
	private $nroObraSocial;
	
	private $obraSocialNombre;

	private $domicilio;

	public function __construct(){

		parent::__construct();

		//agregamos las propiedades a popular en el submit.
		$this->addProperty("nombre");
		$this->addProperty("nroHistoriaClinica");
		$this->addProperty("obraSocialNombre");
		$this->addProperty("nroObraSocial");
		$this->addProperty("domicilio");
		
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
		$criteria->setNroHistoriaClinica( $this->getNroHistoriaClinica() );
		$criteria->setNroObraSocial( $this->getNroObraSocial() );
		$criteria->setObraSocialNombre( $this->getObraSocialNombre() );
		$criteria->setDomicilio( $this->getDomicilio() );
		
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
}
