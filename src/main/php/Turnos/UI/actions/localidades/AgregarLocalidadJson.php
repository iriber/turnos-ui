<?php
namespace Turnos\UI\actions\localidades;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Localidad;

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
 * se realiza el alta de una Localidad.
 * @author bernardo
 * @since 20/08/2013
 */
class AgregarLocalidadJson extends JsonAction{

	
	public function execute(){

		$rasty= RastyMapHelper::getInstance();
		$componentConfig = new ComponentConfig();
	    $componentConfig->setId( "form" );
		$componentConfig->setType( "LocalidadQuickForm" );
		
		//esto setearlo en el .rasty
	    $form = ComponentFactory::buildByType($componentConfig);
	    $form->setMethod( "POST" );
	    
		try {

			//creamos la Localidad.
			$localidad = new Localidad();
			
			//completados con los datos del formulario.
			$form->fillEntity($localidad);
			
			//agregamos la Localidad.
			UIServiceFactory::getUILocalidadService()->add( $localidad );
			
			$result["oid"] = $localidad->getOid();
						
		} catch (RastyException $e) {
		
			$result["error"] = $e->getMessage();
		}
		
		return $result;
		
	}

}
?>