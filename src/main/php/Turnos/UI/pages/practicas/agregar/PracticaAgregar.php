<?php
namespace Turnos\UI\pages\practicas\agregar;

use Turnos\UI\service\finder\NomencladorFinder;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Turnos\Core\model\Practica;
use Turnos\Core\model\ObraSocial;
use Rasty\utils\LinkBuilder;

class PracticaAgregar extends TurnosPage{

	/**
	 * practica a agregar.
	 * @var Practica
	 */
	private $practica;

	public function __construct(){
		
		//inicializamos la práctica.
		$practica = new Practica();
		
		$practica->setFecha( new \DateTime() );
		//el profesional es el logueado
		if( TurnosUtils::isProfesionalLogged() ){
			$practica->setProfesional( TurnosUtils::getProfesionalLogged() );
		}	
			
		$this->setPractica($practica);
		
		$codigoConsulta = TurnosUtils::TRN_PRACTICA_DEFAULT;
		$finder = new NomencladorFinder();
		$practica->setNomenclador( $finder->findEntityByCode($codigoConsulta) );
	}
	
	public function getTitle(){
		return $this->localize( "practica.agregar.title" );
	}

	public function getType(){
		
		return "PracticaAgregar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
	}

	/**
	 * parametro de la página para setear el oid cliente.
	 * @param string $oid
	 */
	public function setClienteOid($clienteOid)
	{
		
		if(!empty($clienteOid) ){

			//a partir del id buscamos el cliente.
			$cliente = UIServiceFactory::getUIClienteService()->get($clienteOid);
		
			$this->getPractica()->setCliente($cliente);
			
			$this->getPractica()->setObraSocial($cliente->getObraSocial());
			
		}	    
		
	}

	/**
	 * parametro de la página para setear el oid profesional.
	 * @param string $oid
	 */
	public function setProfesionalOid($profesionalOid)
	{
		if(!empty($profesionalOid) ){

			//a partir del id buscamos el cliente.
			$profesional = UIServiceFactory::getUIProfesionalService()->get($profesionalOid);
		
			$this->getPractica()->setProfesional($profesional);
		}	    
		
	}

	public function getPractica()
	{
	    return $this->practica;
	}

	public function setPractica($practica)
	{
	    $this->practica = $practica;
	}
	

}
?>