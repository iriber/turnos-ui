<?php
namespace Turnos\UI\actions\turnos;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Turno;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;

use Rasty\app\RastyMapHelper;
use Rasty\factory\ComponentFactory;
use Rasty\factory\ComponentConfig;

/**
 * se modifica un turno x json, es una modificaciones sobre pocos
 * datos.
 * @author bernardo
 * @since 29/04/2014
 */
class ModificarTurnoJson extends JsonAction{

	
	public function execute(){

		$rasty= RastyMapHelper::getInstance();
		
		$componentConfig = new ComponentConfig();
	    $componentConfig->setId( "form" );
		$componentConfig->setType( "TurnoQuickForm" );
		
		//esto setearlo en el .rasty
	    $turnoForm = ComponentFactory::buildByType($componentConfig);
	    $turnoForm->setMethod( "POST" );
	    
	    $oid = $turnoForm->getOid();
	    
		try {

			//obtenemos el turno.
			$turno  = UIServiceFactory::getUITurnoService()->get($oid );
			
			//completados con los datos del formulario.
			$turnoForm->fillEntity($turno);
			
			//actualizamos el turno.
			UIServiceFactory::getUITurnoService()->update( $turno );
			
			$result["oid"] = $turno->getOid();

		} catch (RastyException $e) {
		
			$result["error"] = $e->getMessage();
		}
		
		return $result;
		
	}

}
?>