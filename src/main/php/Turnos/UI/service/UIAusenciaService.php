<?php
namespace Turnos\UI\service;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;

use Cose\criteria\impl\Criteria;

use Turnos\Core\service\ServiceFactory;
use Turnos\Core\criteria\AusenciaCriteria;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\Ausencia;

/**
 * 
 * UI service para Ausencias.
 * 
 * @author bernardo
 * @since 29/08/2013
 */
class UIAusenciaService {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UIAusenciaService();
			
		}
		return self::$instance; 
	}

	
	public function getAusenciasDelDia( \DateTime $fecha, Profesional $profesional){
		
		try{
			
			$service = ServiceFactory::getAusenciaService();
			return $service->getAusenciasDelDia( $fecha, $profesional);
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	
	public function add( Ausencia $ausencia ){

		try {

			$service = ServiceFactory::getAusenciaService();
		
			$service->add( $ausencia );
		
		} catch(DuplicatedEntityException $ex){
		
			$re = new RastyDuplicatedException( $ex->getMessage() );
			$re->setOid($ex->getOid());
			throw $re;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}

	}
	
	
	public function getAusenciasVigentes( \DateTime $fecha, Profesional $profesional){
		
		try{
			
			$service = ServiceFactory::getAusenciaService();
			return $service->getAusenciasVigentes( $fecha, $profesional);
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}

	
	public function getAusenciasVigentesEnRango( \DateTime $fechaDesde, \DateTime $fechaHasta, Profesional $profesional){
		
		try{
			
			$service = ServiceFactory::getAusenciaService();
			return $service->getAusenciasVigentesEnRango( $fechaDesde, $fechaHasta, $profesional);
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	public function delete( $oid ){
		
		try{
		
			$service = ServiceFactory::getAusenciaService();
		
			return $service->delete( $oid );
			
		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}

	
}
?>