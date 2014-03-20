<?php
namespace Turnos\UI\actions\profesionales;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\utils\ReflectionUtils;
use Rasty\exception\RastyException;

use Rasty\i18n\Locale;

/**
 * se consultan profesionales de una especialidad x json
 * 
 * @author bernardo
 * @since 27/02/2014
 */
class ListarProfesionalesByEspecialidadJson extends JsonAction{

	
	public function execute(){

		$result = array();
		
		try {

			$especialidadOid = RastyUtils::getParamGET("oid");
			
			$entities = UIServiceFactory::getUIProfesionalService()->getProfesionalesByEspecialidad( $especialidadOid );
		
			$result["profesionales"] = $this->getProfesionales( $entities, array("oid", "nombre")  );

		} catch (RastyException $e) {
		
			$result["error"] = $e->getMessage();
			
		}
		
		return $result;
		
	}

	private function getProfesionales( $entities, $attributes ){
		
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