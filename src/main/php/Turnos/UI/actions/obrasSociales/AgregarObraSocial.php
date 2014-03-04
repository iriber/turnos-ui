<?php
namespace Turnos\UI\actions\obrasSociales;

use Turnos\UI\components\form\obraSocial\ObraSocialForm;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\ObraSocial;

use Rasty\actions\Action;
use Rasty\actions\Forward;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;
use Rasty\exception\RastyDuplicatedException;


/**
 * se realiza el alta de un ObraSocial.
 * @author bernardo
 * @since 20/02/2014
 */
class AgregarObraSocial extends Action{

	
	public function execute(){

		$forward = new Forward();

		$page = PageFactory::build("ObraSocialAgregar");
		
		$obraSocialForm = $page->getComponentById("obraSocialForm");
		
		try {

			//throw new RastyException("testeando.ando");
			
			//creamos un nuevo ObraSocial.
			$obraSocial = new ObraSocial();
			
			//completados con los datos del formulario.
			$obraSocialForm->fillEntity($obraSocial);
			
			
			//agregamos el ObraSocial.
			UIServiceFactory::getUIObraSocialService()->add( $obraSocial );
			
			
			$forward->setPageName( $obraSocialForm->getBackToOnSuccess() );
			$forward->addParam( "obraSocialOid", $obraSocial->getOid() );			
		
			$obraSocialForm->cleanSavedProperties();
			
		} catch (RastyDuplicatedException $e) {
		
			/*
			$obraSocialOid = $e->getOid();
			if( !empty($obraSocialOid) )
				$linkDuplicado = LinkBuilder::getPageUrl( "HistoriaClinica", array("obraSocialOid"=>$obraSocialOid) );
			*/
			$linkDuplicado = "";
			$forward->setPageName( "ObraSocialAgregar" );
			$forward->addError( $e->getMessage() . $linkDuplicado);
			
			//guardamos lo ingresado en el form.
			$obraSocialForm->save();
		
		} catch (RastyException $e) {
		
			$forward->setPageName( "ObraSocialAgregar" );
			$forward->addError( $e->getMessage() );
			
			//guardamos lo ingresado en el form.
			$obraSocialForm->save();
		}
		
		return $forward;
		
	}

}
?>