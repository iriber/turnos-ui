<?php
namespace Turnos\UI\service;

use Turnos\UI\components\filter\model\UIClienteCriteria;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;
use Rasty\exception\RastyDuplicatedException;
use Cose\exception\DuplicatedEntityException;

use Cose\criteria\impl\Criteria;

use Turnos\Core\service\ServiceFactory;
use Turnos\Core\criteria\ClienteCriteria;
use Turnos\Core\model\Cliente;

use Rasty\Grid\entitygrid\model\IEntityGridService;
use Rasty\Grid\filter\model\UICriteria;


/**
 * 
 * UI service para clientes.
 * 
 * @author bernardo
 * @since 03/08/2013
 */
class UIClienteService implements IEntityGridService{
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UIClienteService();
			
		}
		return self::$instance; 
	}

	
	
	public function getList( UIClienteCriteria $uiCriteria){

		try{
			$criteria = $uiCriteria->buildCoreCriteria() ;
			
			$service = ServiceFactory::getClienteService();
			
			$clientes = $service->getList( $criteria );
	
			return $clientes;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
	}

	
	function getEntitiesCount($uiCriteria){

		try{
			
			$criteria = $uiCriteria->buildCoreCriteria() ;
			
			$service = ServiceFactory::getClienteService();
			$clientes = $service->getCount( $criteria );
			
			return $clientes;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	function getEntities($uiCriteria){
		
		return $this->getList($uiCriteria);
	}
	
	public function add( Cliente $cliente ){

		try {

			$service = ServiceFactory::getClienteService();
		
			$service->add( $cliente );
		
		} catch(DuplicatedEntityException $ex){
		
			$re = new RastyDuplicatedException( $ex->getMessage() );
			$re->setOid($ex->getOid());
			throw $re;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}

	}
	
	public function get( $oid ){

		try{
			
			$service = ServiceFactory::getClienteService();
		
			return $service->get( $oid );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}

	}

	public function update( Cliente $cliente ){
		
		try {

			$service = ServiceFactory::getClienteService();
		
			$service->update( $cliente );
		
		} catch(DuplicatedEntityException $ex){
		
			$re = new RastyDuplicatedException( $ex->getMessage() );
			$re->setOid($ex->getOid());
			throw $re;
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}

	}

	public function getByHistoriaClinica( $nroHistoriaClinica ){

		try{
		
			$service = ServiceFactory::getClienteService();
			
			$criteria = new ClienteCriteria();
			$criteria->setNroHistoriaClinica($nroHistoriaClinica);
			
			return $service->getSingleResult( $criteria );
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}	
		
	}
}
?>