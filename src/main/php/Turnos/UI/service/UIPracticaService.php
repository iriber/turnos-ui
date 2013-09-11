<?php
namespace Turnos\UI\service;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;

use Cose\criteria\impl\Criteria;

use Turnos\Core\service\ServiceFactory;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\Practica;

/**
 * 
 * UI service para prácticas.
 * 
 * @author bernardo
 * @since 15/08/2013
 */
class UIPracticaService {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UIPracticaService();
			
		}
		return self::$instance; 
	}

	public function getHistoriaClinica( Cliente $cliente ){
		
		try{
		
			$service = ServiceFactory::getPracticaService();
			return $service->getHistoriaClinica( $cliente );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
	}
	
	public function add( Practica $practica ){

		try{
		
			$service = ServiceFactory::getPracticaService();
		
			$service->add( $practica );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	

	}
	
	public function get( $oid ){

		try{
		
			$service = ServiceFactory::getPracticaService();
		
			return $service->get( $oid );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	

	}

	public function update( Practica $practica ){

		try{
		
			$service = ServiceFactory::getPracticaService();
		
			$service->update( $practica );

		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	

	}
	
	public function delete( $oid ){

		try{
		
			$service = ServiceFactory::getPracticaService();
		
			return $service->delete( $oid );

		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
	}

}
?>