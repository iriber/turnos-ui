<?php
namespace Turnos\UI\pages\practicas\modificar;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Turnos\Core\model\Practica;
use Rasty\utils\LinkBuilder;
class PracticaModificar extends TurnosPage{

	/**
	 * practica a modificar.
	 * @var Practica
	 */
	private $practica;

	public function __construct(){
		
		//inicializamos la práctica.
		$practica = new Practica();
		$this->setPractica($practica);
		
		
	}
	
	public function getTitle(){
		return $this->localize( "practica.modificar.title" );
	}

	public function getType(){
		
		return "PracticaModificar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
	}

	/**
	 * parametro de la página para setear el oid cliente.
	 * @param string $oid
	 */
	public function setPracticaOid($oid)
	{
		if(!empty($oid) ){

			//a partir del id buscamos el cliente.
			$practica = UIServiceFactory::getUIPracticaService()->get($oid);
		
			$this->setPractica($practica);
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