<?php
namespace Turnos\UI\actions\obrasSociales\planes;

use Turnos\Core\model\ObraSocial;
use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\utils\ReflectionUtils;
use Rasty\exception\RastyException;

use Rasty\i18n\Locale;

/**
 * se consultan planes de una obra social x json
 * 
 * @author bernardo
 * @since 24/04/2014
 */
class ListarPlanesByObraSocialJson extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$osOid = RastyUtils::getParamGET("oid", -1);

			$empty = array(-1, "-- Elija un plan --");
			
			if($osOid > 0){
				
				$os = new ObraSocial();
				$os->setOid($osOid);
				
				$entities = UIServiceFactory::getUIPlanObraSocialService()->getPlanes( $os );

				
				$result["planes"] = $this->getPlanes( $entities, array("oid", "nombre"), $empty  );	
			}else{
				
				$result["planes"] = array($empty);
			}
			
			

		} catch (RastyException $e) {
		
			$result["error"] = $e->getMessage();
			
		}
		
		return $result;
		
	}

	private function getPlanes( $entities, $attributes, $empty=null ){
		
		$result = array();
		
		if($empty!=null)
		$result[] = $empty;
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