<?php
namespace Turnos\UI\pages\nomenclador\modificar;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\service\UIServiceFactory;

use Rasty\utils\XTemplate;
use Turnos\Core\model\Nomenclador;

class NomencladorModificar extends TurnosPage{

	/**
	 * nomenclador a modificar.
	 * @var Nomenclador
	 */
	private $nomenclador;

	
	public function __construct(){
		
		//inicializamos el nomenclador.
		$nomenclador = new Nomenclador();
		$this->setNomenclador($nomenclador);

		
	}
	
	public function setNomencladorOid($oid){
		
		if(!empty($oid)){
			//a partir del id buscamos el nomenclador a modificar.
			$nomenclador = UIServiceFactory::getUINomencladorService()->get($oid);
		
			$this->setNomenclador($nomenclador);
		}
		
	}
	
	public function getTitle(){
		return $this->localize( "nomenclador.modificar.title" );
	}

	public function getType(){
		
		return "NomencladorModificar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
	}


	public function getNomenclador()
	{
	    return $this->nomenclador;
	}

	public function setNomenclador($nomenclador)
	{
	    $this->nomenclador = $nomenclador;
	}
	
}
?>