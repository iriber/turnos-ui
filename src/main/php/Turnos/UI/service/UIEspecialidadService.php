<?php
namespace Turnos\UI\service;

use Turnos\UI\components\filter\model\UIEspecialidadCriteria;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;
use Rasty\exception\RastyDuplicatedException;
use Cose\exception\DuplicatedEntityException;

use Cose\criteria\impl\Criteria;

use Turnos\Core\service\ServiceFactory;
use Turnos\Core\criteria\EspecialidadCriteria;
use Turnos\Core\model\Especialidad;

use Rasty\Grid\entitygrid\model\IEntityGridService;
/**
 * 
 * UI service para Especialidades.
 * 
 * @author bernardo
 * @since 27/02/2014
 */
class UIEspecialidadService implements IEntityGridService {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UIEspecialidadService();
			
		}
		return self::$instance; 
	}

	
	
	public function getList( UIEspecialidadCriteria $uiCriteria){

		try{
			
			$criteria = $uiCriteria->buildCoreCriteria() ;
		
			$service = ServiceFactory::getEspecialidadService();
		
			$criteria->addOrder("nombre", "ASC");
		
			return $service->getList( $criteria );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}

	}
	
	public function getSingleResult( UIEspecialidadCriteria $uiCriteria){

		try{
			
			$criteria = $uiCriteria->buildCoreCriteria() ;
			
			$service = ServiceFactory::getEspecialidadService();
			
			return $service->getSingleResult( $criteria );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	public function get( $oid ){

		try{
			
			$service = ServiceFactory::getEspecialidadService();
		
			return $service->get( $oid );
		
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	function getEntitiesCount($uiCriteria){

		try{
		
			$criteria = $uiCriteria->buildCoreCriteria() ;
		
			$service = ServiceFactory::getEspecialidadService();
			$especialidades = $service->getCount( $criteria );
		
			return $especialidades;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
	}
	
	function getEntities($uiCriteria){

		try{
			
			$criteria = $uiCriteria->buildCoreCriteria() ;
			
			$service = ServiceFactory::getEspecialidadService();
			$especialidades = $service->getList( $criteria );
			
			return $especialidades;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	public function add( Especialidad $especialidad ){

		try{
		
			$service = ServiceFactory::getEspecialidadService();
		
			$service->add( $especialidad );
			
		} catch(DuplicatedEntityException $ex){
		
			$re = new RastyDuplicatedException( $ex->getMessage() );
			$re->setOid($ex->getOid());
			throw $re;
						
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	

	}


	public function update( Especialidad $especialidad ){

		try{
			
			$service = ServiceFactory::getEspecialidadService();
		
			$service->update( $especialidad );

		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	
}
?>