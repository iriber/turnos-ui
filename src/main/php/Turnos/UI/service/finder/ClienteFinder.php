<?php
namespace Turnos\UI\service\finder;

use Turnos\UI\components\filter\model\UIClienteCriteria;

use Turnos\UI\service\UIServiceFactory;

use Rasty\Forms\finder\model\IAutocompleteFinder;

/**
 * 
 * Finder para clientes.
 * 
 * @author bernardo
 * @since 13/08/2013
 */
class ClienteFinder implements  IAutocompleteFinder {
	
	
	public function __construct() {}
	
	/**
	 * (non-PHPdoc)
	 * @see service/finder/Rasty\Forms\finder\model.IAutocompleteFinder::findEntitiesByText()
	 */
	public function findEntitiesByText( $text, $parent=null ){
		
		$uiCriteria = new UIClienteCriteria();
		$uiCriteria->setNombre( $text );
		$uiCriteria->setRowPerPage( 10 );
		return UIServiceFactory::getUIClienteService()->getList($uiCriteria);	
	}
	
	/**
	 * (non-PHPdoc)
	 * @see service/finder/Rasty\Forms\finder\model.IAutocompleteFinder::findEntityByCode()
	 */
	function findEntityByCode( $code, $parent=null ){
		
		//$uiCriteria = new UIObraSocialCriteria();
		//$uiCriteria->setOid( $code );
		
		return UIServiceFactory::getUIClienteService()->get( $code );
	}
	
	/**
	 * (non-PHPdoc)
	 * @see service/finder/Rasty\Forms\finder\model.IAutocompleteFinder::getAttributes()
	 */
	public function getAttributes(){
		return array("nombre", "obraSocial.nombre,nroObraSocial", "telefonoFijo,telefonoMovil,domicilio");		
	}

	/**
	 * (non-PHPdoc)
	 * @see service/finder/Rasty\Forms\finder\model.IAutocompleteFinder::getAttributesCallback()
	 */
	public function getAttributesCallback(){
		return array("oid", "nombre", "fechaAltaFormateada", "fechaNacimientoFormateada", "edad", "obraSocial.oid", "obraSocial.nombre", "nroObraSocial", "nroHistoriaClinica");		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see service/finder/Rasty\Forms\finder\model.IAutocompleteFinder::getEntityCode()
	 */
	function getEntityCode( $entity ){
		if( !empty( $entity)  )
		
		return $entity->getOid();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see service/finder/Rasty\Forms\finder\model.IAutocompleteFinder::getEntityLabel()
	 */
	function getEntityLabel( $entity ){
		if( !empty( $entity)  )
		return $entity->getNombre();
	}

	/**
	 * (non-PHPdoc)
	 * @see service/finder/Rasty\Forms\finder\model.IAutocompleteFinder::getEntityFieldCode()
	 */
	function getEntityFieldCode( $entity ){
		return "oid";
	}
}
?>