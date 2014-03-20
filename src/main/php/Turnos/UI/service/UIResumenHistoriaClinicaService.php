<?php
namespace Turnos\UI\service;

use Turnos\UI\components\filter\model\UIResumenHistoriaClinicaCriteria;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;
use Cose\criteria\impl\Criteria;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\Especialidad;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\ResumenHistoriaClinica;

use Turnos\Core\service\ServiceFactory;
use Cose\Security\model\User;

/**
 * 
 * UI service para ResumenHistoriaClinica.
 * 
 * @author bernardo
 * @since 19/03/2014
 */
class UIResumenHistoriaClinicaService {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UIResumenHistoriaClinicaService();
			
		}
		return self::$instance; 
	}

	public function getResumenHistoriaClinica( Cliente $cliente ){
		
		try{
		
			$service = ServiceFactory::getResumenHistoriaClinicaService();
			return $service->getResumenHistoriaClinica( $cliente );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
	}
	
	public function add( ResumenHistoriaClinica $resumen ){

		try{
		
			$service = ServiceFactory::getResumenHistoriaClinicaService();
		
			$service->add( $resumen );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	

	}
	
	public function get( $oid ){

		try{
		
			$service = ServiceFactory::getResumenHistoriaClinicaService();
		
			return $service->get( $oid );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	

	}

	public function update( ResumenHistoriaClinica $resumen ){

		try{
		
			$service = ServiceFactory::getResumenHistoriaClinicaService();
		
			$service->update( $resumen );

		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	

	}
	
	public function delete( $oid ){

		try{
		
			$service = ServiceFactory::getResumenHistoriaClinicaService();
		
			return $service->delete( $oid );

		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
	}

	public function getList( UIResumenHistoriaClinicaCriteria $uiCriteria){

		try{
			$criteria = $uiCriteria->buildCoreCriteria() ;
			
			$service = ServiceFactory::getResumenHistoriaClinicaService();
			
			$resumenes = $service->getList( $criteria );
	
			return $resumenes;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
	}
	
	
	function getEntities($uiCriteria){
		
		return $this->getList($uiCriteria);

	}
	

	function getEntitiesCount($uiCriteria){

		try{
			
			$criteria = $uiCriteria->buildCoreCriteria() ;
			
			$service = ServiceFactory::getResumenHistoriaClinicaService();
			$resumenes = $service->getCount( $criteria );
			
			return $resumenes;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
}
?>