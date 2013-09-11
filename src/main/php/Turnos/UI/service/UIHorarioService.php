<?php
namespace Turnos\UI\service;

use Rasty\components\RastyPage;
use Rasty\utils\XTemplate;
use Rasty\i18n\Locale;
use Rasty\exception\RastyException;

use Cose\criteria\impl\Criteria;

use Turnos\Core\service\ServiceFactory;
use Turnos\Core\criteria\HorarioCriteria;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\Ausencia;

/**
 * 
 * UI service para horarios.
 * 
 * @author bernardo
 * @since 03/08/2013
 */
class UIHorarioService {
	
	private static $instance;
	
	private function __construct() {}
	
	public static function getInstance() {
		
		if( self::$instance == null ) {
			
			self::$instance = new UIHorarioService();
			
		}
		return self::$instance; 
	}

	
	public function getHorariosDelDia( \DateTime $fecha, Profesional $profesional){
		
		try{
			
			$service = ServiceFactory::getHorarioService();
			return $service->getHorariosDelDia( $fecha, $profesional);
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	public function getHorariosDelProfesional( Profesional $profesional){
		
		try{
			
			$service = ServiceFactory::getHorarioService();
			return $service->getHorariosDelProfesional( $profesional);
			
		} catch (\Exception $e) {
			
			throw new RastyException($e->getMessage());
			
		}
	}
	
	
}
?>