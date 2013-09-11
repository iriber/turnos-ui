<?php
namespace Turnos\UI\service;

use Turnos\UI\components\filter\model\UILocalidadCriteria;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;
use Rasty\exception\RastyDuplicatedException;
use Cose\exception\DuplicatedEntityException;

use Cose\criteria\impl\Criteria;

use Turnos\Core\service\ServiceFactory;
use Turnos\Core\criteria\LocalidadCriteria;
use Turnos\Core\model\Localidad;

use Rasty\Grid\entitygrid\model\IEntityGridService;
/**
 * 
 * UI service para localidades.
 * 
 * @author bernardo
 * @since 03/08/2013
 */
class UILocalidadService implements IEntityGridService {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UILocalidadService();
			
		}
		return self::$instance; 
	}

	
	
	public function getList( UILocalidadCriteria $uiCriteria){

		try{
			
			$criteria = $uiCriteria->buildCoreCriteria() ;
		
			$service = ServiceFactory::getLocalidadService();
		
			$criteria->addOrder("nombre", "ASC");
		
			return $service->getList( $criteria );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}

	}
	
	public function getSingleResult( UILocalidadCriteria $uiCriteria){

		try{
			
			$criteria = $uiCriteria->buildCoreCriteria() ;
			
			$service = ServiceFactory::getLocalidadService();
			
			return $service->getSingleResult( $criteria );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	public function get( $oid ){

		try{
			
			$service = ServiceFactory::getLocalidadService();
		
			return $service->get( $oid );
		
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	function getEntitiesCount($uiCriteria){

		try{
		
			$criteria = $uiCriteria->buildCoreCriteria() ;
		
			$service = ServiceFactory::getLocalidadService();
			$localidades = $service->getCount( $criteria );
		
			return $localidades;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
	}
	
	function getEntities($uiCriteria){

		try{
			
			$criteria = $uiCriteria->buildCoreCriteria() ;
			
			$service = ServiceFactory::getLocalidadService();
			$localidades = $service->getList( $criteria );
			
			return $localidades;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	public function add( Localidad $localidad ){

		try{
		
			$service = ServiceFactory::getLocalidadService();
		
			$service->add( $localidad );
			
		} catch(DuplicatedEntityException $ex){
		
			$re = new RastyDuplicatedException( $ex->getMessage() );
			$re->setOid($ex->getOid());
			throw $re;
						
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	

	}


	public function update( Localidad $localidad ){

		try{
			
			$service = ServiceFactory::getLocalidadService();
		
			$service->update( $localidad );

		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	
}
?>