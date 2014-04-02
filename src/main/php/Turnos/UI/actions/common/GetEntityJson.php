<?php
namespace Turnos\UI\actions\common;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\utils\ReflectionUtils;
use Rasty\exception\RastyException;

use Rasty\i18n\Locale;

/**
 * se consulta una entity x json
 * dado su código
 * 
 * @author bernardo
 * @since 09/08/2013
 */
class GetEntityJson extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$code = RastyUtils::getParamGET("code");
			
			$finderClazz = RastyUtils::getParamGET("finder");
			
			$finder = ReflectionUtils::newInstance( $finderClazz );
			
			$entity = $finder->findEntityByCode( $code );
		
			$result = $this->build($entity, $finder );
			
		} catch (RastyException $e) {
		
			$result["error"] = Locale::localize("entity.not.found")  ;//$e->getMessage();
			
		}
		
		return $result;
		
	}

	private function build( $entity, $finder ){
		$values = array();
		
		
		//va siempre oid + label, + los atributos que defina el finder.
		$values["code"] = $finder->getEntityCode($entity); 
		$values["label"] = $finder->getEntityLabel($entity);
		 
		$attributes = $finder->getAttributesCallback();
		foreach ($attributes as $attribute) {
			$values[$attribute] = ReflectionUtils::doGetter( $entity, $attribute );
		}
		
		
		return $values;
	}
}
?>