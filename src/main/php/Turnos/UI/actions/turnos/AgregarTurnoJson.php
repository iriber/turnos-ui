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
 * se realiza el alta de un turno.
 * @author bernardo
 * @since 20/03/2014
 */
class AgregarTurnoJson extends JsonAction{

	
	public function execute(){

		$rasty= RastyMapHelper::getInstance();
		
		$componentConfig = new ComponentConfig();
	    $componentConfig->setId( "form" );
		$componentConfig->setType( "TurnoQuickForm" );
		
		//esto setearlo en el .rasty
	    $turnoForm = ComponentFactory::buildByType($componentConfig);
	    $turnoForm->setMethod( "POST" );
	    
		try {

			//creamos un nuevo turno.
			$turno = new Turno();
			
			//completados con los datos del formulario.
			$turnoForm->fillEntity($turno);
			
			//agregamos el turno.
			UIServiceFactory::getUITurnoService()->add( $turno );
			
			$result["oid"] = $turno->getOid();

		} catch (RastyException $e) {
		
			$result["error"] = $e->getMessage();
		}
		
		return $result;
		
	}

}
?>