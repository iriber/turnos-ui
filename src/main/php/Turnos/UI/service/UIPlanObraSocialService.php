<?php
namespace Turnos\UI\service;


use Turnos\UI\components\filter\model\UIPlanObraSocialCriteria;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;
use Rasty\exception\RastyDuplicatedException;
use Cose\exception\DuplicatedEntityException;

use Cose\criteria\impl\Criteria;

use Turnos\Core\service\ServiceFactory;
use Turnos\Core\model\ObraSocial;
use Turnos\Core\model\PlanObraSocial;

use Rasty\Grid\entitygrid\model\IEntityGridService;

/**
 * 
 * UI service para planes de obras Sociales.
 * 
 * @author bernardo
 * @since 24/04/2014
 */
class UIPlanObraSocialService  implements IEntityGridService{
	
	private static $instance;
	
	public function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UIPlanObraSocialService();
			
		}
		return self::$instance; 
	}

	
	
	public function getList( UIPlanObraSocialCriteria $uiCriteria){

		$criteria = $uiCriteria->buildCoreCriteria() ;
		
		$service = ServiceFactory::getPlanObraSocialService();
		
		$criteria->addOrder("nombre", "ASC");
		
		$planes = $service->getList( $criteria );

		return $planes;
	}
	
	public function getSingleResult( UIPlanObraSocialCriteria $uiCriteria){

		$criteria = $uiCriteria->buildCoreCriteria() ;
		
		$service = ServiceFactory::getPlanObraSocialService();
		
		return $service->getSingleResult( $criteria );
		
	}
	
	public function get( $oid ){

		$service = ServiceFactory::getPlanObraSocialService();
		
		return $service->get( $oid );
		
	}
	
	function getEntitiesCount($uiCriteria){

		$criteria = $uiCriteria->buildCoreCriteria() ;
		
		$service = ServiceFactory::getPlanObraSocialService();
		$planes = $service->getCount( $criteria );
		
		return $planes;
	}
	
	function getEntities($uiCriteria){
		
		$criteria = $uiCriteria->buildCoreCriteria() ;
		
		$service = ServiceFactory::getPlanObraSocialService();
		$clientes = $service->getList( $criteria );
		
		return $clientes;
	}
	
	public function add( PlanObraSocial $planObraSocial ){

		try {

			$service = ServiceFactory::getPlanObraSocialService();
		
			$service->add( $planObraSocial );
		
		} catch(DuplicatedEntityException $ex){
		
			$re = new RastyDuplicatedException( $ex->getMessage() );
			$re->setOid($ex->getOid());
			throw $re;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
		

	}


	public function update( PlanObraSocial $planObraSocial ){

		try {
			
			$service = ServiceFactory::getPlanObraSocialService();
			
			$service->update( $planObraSocial );	
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
		

	}
	
	/**
	 * se obtienen los planes de una obra social
	 * @param ObraSocial $obraSocial
	 */
	public function getPlanes( ObraSocial $obraSocial ){
		
		try {
			
			$service = ServiceFactory::getPlanObraSocialService();
			
			return $service->getPlanes( $obraSocial );	
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
		
	}
	
	public function delete( $oid ){
		
		try{
		
			$service = ServiceFactory::getPlanObraSocialService();
		
			return $service->delete( $oid );
			
		} catch (ServiceException $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
}
?>