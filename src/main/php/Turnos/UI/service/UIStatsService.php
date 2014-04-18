<?php
namespace Turnos\UI\service;

use Turnos\UI\components\filter\model\UIPracticaCriteria;

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
 * UI service para stats.
 * 
 * @author bernardo
 * @since 09/04/2014
 */
class UIStatsService {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UIStatsService();
			
		}
		return self::$instance; 
	}

	/**
	 * total de clientes del mes, día a día
	 * puede ser para un profesional específico o para todos.
	 * 
	 * @param unknown_type $mes
	 * @param Profesional $profesional
	 */
	public function getClientesMes( $mes, Profesional $profesional=null){
		
//		$service = ServiceFactory::getTurnoService();
//		
//		return $service->getList( $criteria );
		
		
	}
	
	public function getClientesPorMes( $anio, Profesional $profesional=null ){
		
		$service = ServiceFactory::getStatsService();
		
		return $service->getClientesPorMes( $anio );
		
	}
}
?>