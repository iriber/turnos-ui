<?php
namespace Turnos\UI\actions\obrasSociales;

use Turnos\UI\components\form\obraSocial\ObraSocialQuickForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\ObraSocial;

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
 * se realiza el alta de una obra social.
 * @author bernardo
 * @since 20/08/2013
 */
class AgregarObraSocialJson extends JsonAction{

	
	public function execute(){

		$rasty= RastyMapHelper::getInstance();
		$componentConfig = new ComponentConfig();
	    $componentConfig->setId( "form" );
		$componentConfig->setType( "ObraSocialQuickForm" );
		
		//esto setearlo en el .rasty
	    $osForm = ComponentFactory::buildByType($componentConfig);
	    $osForm->setMethod( "POST" );
	    
		try {

			//creamos la obra social.
			$os = new ObraSocial();
			
			//completados con los datos del formulario.
			$osForm->fillEntity($os);
			
			//agregamos la obra social.
			UIServiceFactory::getUIObraSocialService()->add( $os );
			
			
			$result["oid"] = $os->getOid();
						
		} catch (RastyException $e) {
		
			$result["error"] = $e->getMessage();
		}
		
		return $result;
		
	}

}
?>