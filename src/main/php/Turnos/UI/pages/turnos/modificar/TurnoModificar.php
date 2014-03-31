<?php
namespace Turnos\UI\pages\turnos\modificar;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\service\UIServiceFactory;

use Rasty\utils\XTemplate;
use Turnos\Core\model\Turno;

class TurnoModificar extends TurnosPage{

	/**
	 * turno a modificar.
	 * @var Turno
	 */
	private $turno;

	
	public function __construct(){
		
		//inicializamos el turno.
		$turno = new Turno();
		
		$this->setTurno($turno);
		
	}
	
	public function setTurnoOid($oid){
		
		if(!empty($oid)){
			//a partir del id buscamos el turno a modificar.
			$turno = UIServiceFactory::getUITurnoService()->get($oid);
		
			$this->setTurno($turno);
		}
		
	}
	
	public function getTitle(){
		return $this->localize( "turno.modificar.title" );
	}

	public function getType(){
		
		return "TurnoModificar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
	}


	public function getTurno()
	{
	    return $this->turno;
	}

	public function setTurno($turno)
	{
		//si aún no fue validado y tiene importe 0, le pongo importe null
		//asi lo obligo a cargarlo.
		$cliente = $turno->getCliente();
		if( empty($cliente) ){
			$turno->setImporte( null );
		}
		
	    $this->turno = $turno;
	}
	
}
?>