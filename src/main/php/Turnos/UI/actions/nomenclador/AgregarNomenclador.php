<?php
namespace Turnos\UI\actions\nomenclador;

use Turnos\UI\components\form\nomenclador\NomencladorForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Nomenclador;

use Rasty\actions\Action;
use Rasty\actions\Forward;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;
use Rasty\exception\RastyDuplicatedException;


/**
 * se realiza el alta de un Nomenclador.
 * @author bernardo
 * @since 20/02/2014
 */
class AgregarNomenclador extends Action{

	
	public function execute(){

		$forward = new Forward();

		$page = PageFactory::build("NomencladorAgregar");
		
		$nomencladorForm = $page->getComponentById("nomencladorForm");
		
		try {

			//throw new RastyException("testeando.ando");
			
			//creamos un nuevo Nomenclador.
			$nomenclador = new Nomenclador();
			
			//completados con los datos del formulario.
			$nomencladorForm->fillEntity($nomenclador);
			
			
			//agregamos el Nomenclador.
			UIServiceFactory::getUINomencladorService()->add( $nomenclador );
			
			
			$forward->setPageName( $nomencladorForm->getBackToOnSuccess() );
			$forward->addParam( "nomencladorOid", $nomenclador->getOid() );			
		
			$nomencladorForm->cleanSavedProperties();
			
		} catch (RastyDuplicatedException $e) {
		
			/*
			$nomencladorOid = $e->getOid();
			if( !empty($nomencladorOid) )
				$linkDuplicado = LinkBuilder::getPageUrl( "HistoriaClinica", array("nomencladorOid"=>$nomencladorOid) );
			*/
			$linkDuplicado = "";
			$forward->setPageName( "NomencladorAgregar" );
			$forward->addError( $e->getMessage() . $linkDuplicado);
			
			//guardamos lo ingresado en el form.
			$nomencladorForm->save();
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "NomencladorAgregar" );
			$forward->addError( $e->getMessage() );
			
			//guardamos lo ingresado en el form.
			$nomencladorForm->save();
		}
		
		return $forward;
		
	}

}
?>