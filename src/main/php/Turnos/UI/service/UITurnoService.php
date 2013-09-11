<?php
namespace Turnos\UI\service;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\utils\Logger;
use Rasty\exception\RastyException;
use Rasty\exception\RastyDuplicatedException;

use Cose\criteria\impl\Criteria;
use Cose\exception\ServiceException;
use Cose\exception\DuplicatedEntityException;

use Turnos\Core\service\ServiceFactory;
use Turnos\Core\criteria\TurnoCriteria;
use Turnos\Core\model\Profesional;
use Turnos\Core\model\Turno;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\EstadoTurno;



/**
 * 
 * UI service para turnos.
 * 
 * @author bernardo
 * @since 03/08/2013
 */
class UITurnoService {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UITurnoService();
			
		}
		return self::$instance; 
	}

	public function getList( UITurnoCriteria $uiCriteria){

		$criteria = $uiCriteria->buildCoreCriteria() ;
		
		$service = ServiceFactory::getTurnoService();
		
		$criteria->addOrder("fecha");
		$criteria->addOrder("hora");
		
		return $service->getList( $criteria );
		
	}
		
	public function getTurnosDelDia( \DateTime $fecha, Profesional $profesional){
		
		$service = ServiceFactory::getTurnoService();
		return $service->getTurnosDelDia( $fecha, $profesional);
	}

	public function getTurnosSemana( \DateTime $fechaDesde, \DateTime $fechaHasta, Profesional $profesional){
		
		$service = ServiceFactory::getTurnoService();
		
		$criteria = new TurnoCriteria();
		$criteria->setFechaDesde($fechaDesde);
		$criteria->setFechaHasta($fechaHasta);
		$criteria->setProfesional($profesional);
		$criteria->addOrder("fecha", "ASC");
		$criteria->addOrder("hora", "ASC");
		
		return $service->getList( $criteria );
	}
	
	public function getTurnosDelDiaEstados( \DateTime $fecha, Profesional $profesional, $estados){
		
		$service = ServiceFactory::getTurnoService();
		
		$criteria = new TurnoCriteria();
		$criteria->setEstados($estados);
		$criteria->setFecha($fecha);
		$criteria->setProfesional($profesional);
		$criteria->addOrder("fecha", "ASC");
		$criteria->addOrder("hora", "ASC");
		
		return $service->getList( $criteria );
	}
	
	public function getTurnosCliente( Cliente $cliente){
		
		$service = ServiceFactory::getTurnoService();
		return $service->getTurnosCliente( $cliente );
	}
	
	public function getTurnoEnCursoCliente( Cliente $cliente){
		
		$service = ServiceFactory::getTurnoService();
		
		$criteria = new TurnoCriteria();
		$criteria->setCliente($cliente);
		$criteria->setEstadoTurno( EstadoTurno::EnCurso );
		$criteria->setMaxResult(1);
		$turno = null;
		try {
			
			$turnos = $service->getList( $criteria );
			if( count($turnos) > 0)
				$turno = $turnos[0];
		} catch (\Exception $e) {
			Logger::log($e->getMessage(), __CLASS__);
		}
		
		return $turno;
	}
	
	public function add( Turno $turno ){

		try{
			
			$service = ServiceFactory::getTurnoService();
		
			$service->add( $turno );

		
		} catch(DuplicatedEntityException $ex){
		
			$re = new RastyDuplicatedException( $ex->getMessage() );
			$re->setOid($ex->getOid());
			throw $re;
			
		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
			
		}
			
	}
	
	public function get( $oid ){

		$service = ServiceFactory::getTurnoService();
		
		return $service->get( $oid );

	}

	public function update( Turno $turno ){

		try{
			
			$service = ServiceFactory::getTurnoService();
		
			$service->update( $turno );

		
		} catch(DuplicatedEntityException $ex){
		
			$re = new RastyDuplicatedException( $ex->getMessage() );
			$re->setOid($ex->getOid());
			throw $re;
			
		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
			
		}
		
	}
	
	public function delete( $oid ){
		
		try{
		
			$service = ServiceFactory::getTurnoService();
		
			return $service->delete( $oid );
			
		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}

	public function iniciar( $oid ){

		try{
		
			$service = ServiceFactory::getTurnoService();
		
			return $service->iniciarTurno( $oid );

		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
			
		}			
	}
	
	public function finalizar( $oid ){

		try{
			
			$service = ServiceFactory::getTurnoService();
		
			return $service->finalizarTurno( $oid );

		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	public function enSala( $oid ){

		try{
			
			$service = ServiceFactory::getTurnoService();
		
			return $service->turnoEnSala( $oid );

		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	public function asignar( $oid ){

		try{
			
			$service = ServiceFactory::getTurnoService();
		
			return $service->asignarTurno( $oid );

		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
}
?>