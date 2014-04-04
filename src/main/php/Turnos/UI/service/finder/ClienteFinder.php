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
		
		//return UIServiceFactory::getUIClienteService()->get( $code );
		
		if(!empty($code))
		return UIServiceFactory::getUIClienteService()->getByHistoriaClinica($code);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see service/finder/Rasty\Forms\finder\model.IAutocompleteFinder::getAttributes()
	 */
	public function getAttributes(){
		return array("nroHistoriaClinica","nombre", "oid", "obraSocial.nombre,nroObraSocial", "telefonoFijo,telefonoMovil,domicilio");		
	}

	/**
	 * (non-PHPdoc)
	 * @see service/finder/Rasty\Forms\finder\model.IAutocompleteFinder::getAttributesCallback()
	 */
	public function getAttributesCallback(){
		return array("nroHistoriaClinica","nombre", "oid", "fechaAltaFormateada", "fechaNacimientoFormateada", "edad", "obraSocial.oid", "obraSocial.nombre", "nroObraSocial", "tipoAfiliado");		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see service/finder/Rasty\Forms\finder\model.IAutocompleteFinder::getEntityCode()
	 */
	function getEntityCode( $entity ){
		if( !empty( $entity)  )
		
		//return $entity->getOid();
		return $entity->getNroHistoriaClinica();
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
		//return "oid";
		return "nroHistoriaClinica";
	}
	
	/**
	 * mensaje para cuando no hay resultados.
	 * @var string
	 */
	function getEmptyResultLabel(){
		return null;
	}
	
	/**
	 * label para agregar una nueva entity
	 * @var string
	*/
	function getAddEntityLabel(){
		return null;
	}
	
	/**
	 * función javascript a ejecutar para agregar una nueva entity.
	 * si esta property es definida se muestra el link cuando
	 * no hay resultados
	 * @var string
	*/
	function getOnclickAdd(){
		return "findentity_cliente_show_addentity()";
	}
	
}
?>