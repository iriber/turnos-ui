<?php
namespace Turnos\UI\pages\historia\modificar;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Turnos\Core\model\ResumenHistoriaClinica;
use Rasty\utils\LinkBuilder;

class ResumenHistoriaClinicaModificar extends TurnosPage{

	/**
	 * ResumenHistoriaClinica a modificar.
	 * @var ResumenHistoriaClinica
	 */
	private $resumenHistoriaClinica;

	public function __construct(){
		
		//inicializamos la práctica.
		$resumenHistoriaClinica = new ResumenHistoriaClinica();
		$this->setResumenHistoriaClinica($resumenHistoriaClinica);
		
		
	}
	
	public function getTitle(){
		return $this->localize( "resumenHistoriaClinica.modificar.title" );
	}

	public function getType(){
		
		return "ResumenHistoriaClinicaModificar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
	}

	/**
	 * parametro de la página para setear el oid cliente.
	 * @param string $oid
	 */
	public function setResumenHistoriaClinicaOid($oid)
	{
		if(!empty($oid) ){

			//a partir del id buscamos el cliente.
			$resumenHistoriaClinica = UIServiceFactory::getUIResumenHistoriaClinicaService()->get($oid);
		
			$this->setResumenHistoriaClinica($resumenHistoriaClinica);
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