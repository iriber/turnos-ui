<?php
namespace Turnos\UI\pages\historia\agregar;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Turnos\Core\model\ResumenHistoriaClinica;
use Rasty\utils\LinkBuilder;

class ResumenHistoriaClinicaAgregar extends TurnosPage{

	/**
	 * ResumenHistoriaClinica a agregar.
	 * @var ResumenHistoriaClinica
	 */
	private $resumenHistoriaClinica;

	public function __construct(){
		
		//inicializamos el ResumenHistoriaClinica.
		$resumenHistoriaClinica = new ResumenHistoriaClinica();
		
		$resumenHistoriaClinica->setFecha( new \DateTime() );
		//el profesional es el logueado
		if( TurnosUtils::isProfesionalLogged() ){
			$resumenHistoriaClinica->setProfesional( TurnosUtils::getProfesionalLogged() );
		}	
			
		$this->setResumenHistoriaClinica($resumenHistoriaClinica);
		
	}
	
	public function getTitle(){
		return $this->localize( "resumenHistoriaClinica.agregar.title" );
	}

	public function getType(){
		
		return "ResumenHistoriaClinicaAgregar";
		
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
		
			$this->getResumenHistoriaClinica()->setCliente($cliente);
			
			//rellenamos el resumen con toda la historia clínica que tenemos del paciente
			//las observaciones de las prácticas.
			$historia = UIServiceFactory::getUIPracticaService()->getHistoriaClinica($cliente);
			
			$resumen = "";
			foreach ($historia as $practica) {
				$resumen .= TurnosUtils::formatDateToView($practica->getFecha(), "D j-M-y");
				$resumen .= "\r\n";
				$resumen .= "- ".$practica->getObservaciones();
				$resumen .= "\r\n";
				$resumen .= "\r\n";
			}
			$this->getResumenHistoriaClinica()->setTexto( $resumen );
			
			
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
		
			$this->getResumenHistoriaClinica()->setProfesional($profesional);
			
		}	    
		
	}

	public function getResumenHistoriaClinica()
	{
	    return $this->resumenHistoriaClinica;
	}

	public function setResumenHistoriaClinica($resumenHistoriaClinica)
	{
	    $this->resumenHistoriaClinica = $resumenHistoriaClinica;
	}
	

}
?>