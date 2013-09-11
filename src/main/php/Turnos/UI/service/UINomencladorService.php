<?php
namespace Turnos\UI\service;

use Turnos\UI\components\filter\model\UINomencladorCriteria;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;

use Cose\criteria\impl\Criteria;

use Turnos\Core\service\ServiceFactory;
use Turnos\Core\model\Nomenclador;
use Turnos\Core\criteria\NomencladorCriteria;

use Rasty\Grid\entitygrid\model\IEntityGridService;

/**
 * 
 * UI service para nomenclador.
 * 
 * @author bernardo
 * @since 03/08/2013
 */
class UINomencladorService  implements IEntityGridService {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UINomencladorService();
			
		}
		return self::$instance; 
	}

	
	public function getList( UINomencladorCriteria $uiCriteria){

		try{
		
			$criteria = $uiCriteria->buildCoreCriteria() ;
		
			$service = ServiceFactory::getNomencladorService();
		
			return $service->getList( $criteria );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	

	}
	
	public function getSingleResult( UINomencladorCriteria $uiCriteria){

		try{
		
			$criteria = $uiCriteria->buildCoreCriteria() ;
		
			$service = ServiceFactory::getNomencladorService();
		
			return $service->getSingleResult( $criteria );
		
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	public function get( $oid ){

		try{
		
			$service = ServiceFactory::getNomencladorService();
		
			return $service->get( $oid );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
		
	}
	
	public function getByCodigo( $codigo ){

		try{
		
			$service = ServiceFactory::getNomencladorService();
			
			$criteria = new NomencladorCriteria();
			$criteria->setCodigo($codigo);
			
			return $service->getSingleResult( $criteria );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
		
	}
	function getEntitiesCount($uiCriteria){

		try{
		
			$criteria = $uiCriteria->buildCoreCriteria() ;
		
			$service = ServiceFactory::getNomencladorService();
			return  $service->getCount( $criteria );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
	}
	
	function getEntities($uiCriteria){
		
		try{
		
			$criteria = $uiCriteria->buildCoreCriteria() ;
		
			$service = ServiceFactory::getNomencladorService();
		
			return $service->getList( $criteria );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
		
	}
	
	public function add( Nomenclador $nomenclador ){

		try{
		
			$service = ServiceFactory::getNomencladorService();
		
			$service->add( $nomenclador );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	

	}


	public function update( Nomenclador $nomenclador ){

		try{
			
			$service = ServiceFactory::getNomencladorService();
		
			$service->update( $nomenclador );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}

	}
		
}
?>