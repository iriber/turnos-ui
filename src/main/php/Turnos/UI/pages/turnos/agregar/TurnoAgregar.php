<?php
namespace Turnos\UI\pages\turnos\agregar;

use Turnos\UI\service\finder\NomencladorFinder;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\LinkBuilder;

use Turnos\Core\model\Turno;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\ObraSocial;



class TurnoAgregar extends TurnosPage{

	/**
	 * turno a agregar.
	 * @var Turno
	 */
	private $turno;

	public function __construct(){
		
		//inicializamos el turno.
		$turno = new Turno();
		$hora = new \DateTime();
		$hora->setTime(0,0,0);
		$turno->setHora( $hora );
		$turno->setEstado( EstadoTurno::Asignado );
		
		//la fecha es la de la agenda
		if( TurnosUtils::isFechaAgenda() )
			$turno->setFecha( TurnosUtils::getFechaAgenda() );
		else
			$turno->setFecha( new \DateTime() );

		//el profesional es el de la agenda
		if( TurnosUtils::isProfesionalAgenda() ){
			$turno->setProfesional( TurnosUtils::getProfesionalAgenda() );
		}	
		
		//el nomenclador default es la consulta.
		$codigoConsulta = TurnosUtils::TRN_PRACTICA_DEFAULT;
		$finder = new NomencladorFinder();
		$turno->setNomenclador( $finder->findEntityByCode($codigoConsulta) );
		
		$turno->setObraSocial( new ObraSocial() );

		//$turno->setImporte( 0 );
		
		$this->setTurno($turno);

		$this->setearDuracion();
		
		$this->backTo = "TurnosHome";		
	}
	
	public function getTitle(){
		return $this->localize( "turno.agregar.title" );
	}

	public function getType(){
		
		return "TurnoAgregar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){

		$turnoForm = $this->getComponentById("turnoForm");
		$turnoForm->getComponentById("hora")->setIsEditable(true);
		
	}

	public function getTurno()
	{
	    return $this->turno;
	}

	public function setTurno($turno)
	{
	    $this->turno = $turno;
	}

	/**
	 * parametro de la página para setear la hora del turno.
	 * @param string (H:i) $hora
	 */
	public function setHora($hora)
	{
		if( !empty($hora) ){
				$arrayHoraMinutos = explode(":", $hora);
				if( count($arrayHoraMinutos)>1 ){
				$time = new \DateTime();
				$time->setTime($arrayHoraMinutos[0],$arrayHoraMinutos[1],0);
				$this->getTurno()->setHora( $time );
				
				$this->setearDuracion();
				
			}
		}
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
		
			$this->getTurno()->setCliente($cliente);
			
			$this->getTurno()->setClienteObraSocial($cliente->getClienteObraSocial());
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
		
			$this->getTurno()->setProfesional($profesional);
		}	    
		
	}
	
	/**
	 * parametro de la página para setear la fecha del turno.
	 * @param string (Y-m-d) $fecha
	 */
	public function setFecha($fecha){
		if( !empty($fecha) ){
				$arrayFecha = explode("-", $fecha);
				if( count($arrayFecha)>2 ){
					$f = new \DateTime();
					$f->setDate($arrayFecha[0],$arrayFecha[1],$arrayFecha[2]);
					$this->getTurno()->setFecha( $f );
					
					$this->setearDuracion();
				}
		}
	}
	
	public function getLinkActionAgregarPractica(){
		
		return LinkBuilder::getActionUrl( "AgregarPractica") ;
		
	}
	
	public function getMsgError(){
		return "";
	}
	
	public function setearDuracion(){
		
		//dada la fecha y hora buscamos cuánto sería la duración estándar del turno.
		
		$hora = $this->getTurno()->getHora();
		$horariosDelDia = UIServiceFactory::getUIHorarioService()->getHorariosDelDia($this->getTurno()->getFecha(), $this->getTurno()->getProfesional() );
		$duracionTurno = 15;
		foreach ($horariosDelDia as $horario) {
			
			if( $horario->incluyeHora( $hora ) )
				$duracionTurno = $horario->getDuracionTurno() ;
			
		}
		
		$this->getTurno()->setDuracion( $duracionTurno );
		
	}
}
?>