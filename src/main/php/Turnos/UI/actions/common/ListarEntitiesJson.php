<?php
namespace Turnos\UI\actions\common;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\utils\ReflectionUtils;
use Rasty\exception\RastyException;

use Rasty\i18n\Locale;

/**
 * se consultan entities x json
 * 
 * @author bernardo
 * @since 07/08/2013
 */
class ListarEntitiesJson extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$query = RastyUtils::getParamGET("query");
			
			$finderClazz = RastyUtils::getParamGET("finder");
			
			$finder = ReflectionUtils::newInstance( $finderClazz );
			
			$entities = $finder->findEntitiesByText( $query );
		
			$result["entities"] = $this->getEntities( $entities, $finder->getAttributes() );
			
		} catch (RastyException $e) {
		
			$result["error"] = $e->getMessage();
			
		}
		
		return $result;
		
	}

	private function getEntities( $entities, $attributes ){
		
		$result = array();
		
		foreach ($entities as $entity) {
			
			$next = $this->build( $entity, $attributes );
			
			$result[] = $next;
		}
		
		return $result;
	}
	
	private function build( $entity, $attributes ){
		$values = array();
		foreach ($attributes as $attribute) {
			$values[] = ReflectionUtils::doGetter( $entity, $attribute );
			
		}
		return $values;
	}
}
?>