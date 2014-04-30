<?php

namespace Turnos\UI\components\obrasSociales\planes;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;

use Rasty\utils\XTemplate;

use Turnos\Core\model\ObraSocial;
use Turnos\Core\model\PlaneObraSocial;

use Rasty\utils\LinkBuilder;

/**
 * Planes de una obra social.
 * 
 * @author bernardo
 * @since 24/04/2014
 */
class PlanesObraSocial extends RastyComponent{
		
	
	/**
	 * obra social del cual se listan los planes
	 * @var ObraSocial
	 */
	private $obraSocial;

	
	public function __construct(){
		
		$this->setObraSocial( new ObraSocial() ); 
	}
	
	public function getType(){
		
		return "PlanesObraSocial";
		
	}

	protected function parseXTemplate(XTemplate $xtpl){

		$cantidad_ver = 1;
		
		if( $this->getObraSocial() == null ){
			return;
		}
			
		$planes = UIServiceFactory::getUIPlanObraSocialService()->getPlanes( $this->getObraSocial() );
		
		$xtpl->assign("obraSocial_oid", $this->getObraSocial()->getOid() );
		
		$xtpl->assign("nombre_label", $this->localize( "planObraSocial.nombre" ) );

		$xtpl->assign("borrar_label", $this->localize( "planObraSocial.borrar" ) );

		foreach ($planes as $plan) {
			
			$xtpl->assign("plan_oid",  $plan->getOid() );
			$xtpl->assign("nombre", $plan->getNombre() );
			$xtpl->assign("linkBorrar",  LinkBuilder::getActionAjaxUrl( "BorrarPlanObraSocial") );
			
			$xtpl->parse("main.plan");
				
		}
		
	}
	
	

	public function setObraSocialOid($obraSocialOid)
	{
		if(!empty($obraSocialOid) ){

			//a partir del id buscamos el ObraSocial.
			$obraSocial = UIServiceFactory::getUIObraSocialService()->get($obraSocialOid);
		
			$this->setObraSocial($obraSocial);
		}
	    
		
	}

	protected function initObserverEventType(){

		$this->addEventType( "ObraSocial" );
		$this->addEventType( "PlanObraSocial" );
	}

	public function getObraSocial()
	{
	    return $this->obraSocial;
	}

	public function setObraSocial($obraSocial)
	{
	    $this->obraSocial = $obraSocial;
	}
	
	
}
?>