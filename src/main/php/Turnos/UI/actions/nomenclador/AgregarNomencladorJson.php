<?php
namespace Turnos\UI\actions\nomenclador;

use Turnos\UI\components\form\nomenclador\NomencladorQuickForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Nomenclador;

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
 * se realiza el alta de una práctica del nomenclador
 * @author bernardo
 * @since 31/08/2013
 */
class AgregarNomencladorJson extends JsonAction{

	
	public function execute(){

		$rasty= RastyMapHelper::getInstance();
		$componentConfig = new ComponentConfig();
	    $componentConfig->setId( "form" );
		$componentConfig->setType( "NomencladorQuickForm" );
		
		//esto setearlo en el .rasty
	    $form = ComponentFactory::buildByType($componentConfig);
	    $form->setMethod( "POST" );
	    
		try {

			//creamos la entity.
			$nomen = new Nomenclador();
			
			//completados con los datos del formulario.
			$form->fillEntity($nomen);
			
			//agregamos la entity.
			UIServiceFactory::getUINomencladorService()->add( $nomen );
			
			$result["oid"] = $nomen->getOid();
			$result["nombre"] = $nomen->getNombre();
			$result["codigo"] = $nomen->getCodigo();
						
		} catch (RastyException $e) {
		
			$result["error"] = $e->getMessage();
		}
		
		return $result;
		
	}

}
?>