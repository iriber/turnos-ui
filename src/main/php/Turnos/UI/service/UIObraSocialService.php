<?php
namespace Turnos\UI\service;


use Turnos\UI\components\filter\model\UIObraSocialCriteria;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;
use Rasty\exception\RastyDuplicatedException;
use Cose\exception\DuplicatedEntityException;

use Cose\criteria\impl\Criteria;

use Turnos\Core\service\ServiceFactory;
use Turnos\Core\criteria\ObraSocialCriteria;
use Turnos\Core\model\ObraSocial;

use Rasty\Grid\entitygrid\model\IEntityGridService;

/**
 * 
 * UI service para obrasSociales.
 * 
 * @author bernardo
 * @since 03/08/2013
 */
class UIObraSocialService  implements IEntityGridService{
	
	private static $instance;
	
	public function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UIObraSocialService();
			
		}
		return self::$instance; 
	}

	
	
	public function getList( UIObraSocialCriteria $uiCriteria){

		$criteria = $uiCriteria->buildCoreCriteria() ;
		
		$service = ServiceFactory::getObraSocialService();
		
		$criteria->addOrder("nombre", "ASC");
		
		$obrasSociales = $service->getList( $criteria );

		return $obrasSociales;
	}
	
	public function getSingleResult( UIObraSocialCriteria $uiCriteria){

		$criteria = $uiCriteria->buildCoreCriteria() ;
		
		$service = ServiceFactory::getObraSocialService();
		
		return $service->getSingleResult( $criteria );
		
	}
	
	public function get( $oid ){

		$service = ServiceFactory::getObraSocialService();
		
		return $service->get( $oid );
		
	}
	
	function getEntitiesCount($uiCriteria){

		$criteria = $uiCriteria->buildCoreCriteria() ;
		
		$service = ServiceFactory::getObraSocialService();
		$clientes = $service->getCount( $criteria );
		
		return $clientes;
	}
	
	function getEntities($uiCriteria){
		
		$criteria = $uiCriteria->buildCoreCriteria() ;
		
		$service = ServiceFactory::getObraSocialService();
		$clientes = $service->getList( $criteria );
		
		return $clientes;
	}
	
	public function add( ObraSocial $obraSocial ){

		try {

			$service = ServiceFactory::getObraSocialService();
		
			$service->add( $obraSocial );
		
		} catch(DuplicatedEntityException $ex){
		
			$re = new RastyDuplicatedException( $ex->getMessage() );
			$re->setOid($ex->getOid());
			throw $re;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
		

	}


	public function update( ObraSocial $obraSocial ){

		try {
			
			$service = ServiceFactory::getObraSocialService();
			
			$service->update( $obraSocial );	
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
		

	}
	
	
}
?>