<?php
namespace Turnos\UI\service;

use Turnos\UI\components\filter\model\UIProfesionalCriteria;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;
use Cose\criteria\impl\Criteria;

use Turnos\Core\service\ServiceFactory;
use Cose\Security\model\User;

/**
 * 
 * UI service para profesionales.
 * 
 * @author bernardo
 * @since 03/08/2013
 */
class UIProfesionalService {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UIProfesionalService();
			
		}
		return self::$instance; 
	}

	
	
	public function getList( UIProfesionalCriteria $uiCriteria){

		$criteria = $uiCriteria->buildCoreCriteria() ;
		
		$service = ServiceFactory::getProfesionalService();
		
		$profesionales = $service->getList( $criteria );

		return $profesionales;
	}
	
	public function getProfesionalByUser(User $user){
		
		$service = ServiceFactory::getProfesionalService();
		
		$profesional = $service->getProfesionalByUser($user);

		return $profesional;
	}
	
	public function get( $oid ){

		$service = ServiceFactory::getProfesionalService();
		
		return $service->get( $oid );

	}
	
	
}
?>