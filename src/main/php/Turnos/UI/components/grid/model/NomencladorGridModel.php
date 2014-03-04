<?php
namespace Turnos\UI\components\grid\model;

use Turnos\UI\components\filter\model\UINomencladorCriteria;

use Rasty\Grid\entitygrid\EntityGrid;
use Rasty\Grid\entitygrid\model\EntityGridModel;
use Rasty\Grid\entitygrid\model\GridModelBuilder;
use Rasty\Grid\filter\model\UICriteria;

use Turnos\UI\service\UIServiceFactory;
use Rasty\utils\RastyUtils;
use Rasty\utils\Logger;

use Rasty\Menu\menu\model\MenuOption;
use Rasty\Menu\menu\model\MenuGroup;

/**
 * Model para la grilla de Nomenclador.
 * @author bernardo
 * @since 15/08/2013
 */
class NomencladorGridModel extends EntityGridModel{

	public function __construct() {

        parent::__construct();
        $this->initModel();
        
    }
    
    public function getService(){
    	
    	return UIServiceFactory::getUINomencladorService();
    }
    
    public function getFilter(){
    	
    	$componentConfig = new ComponentConfig();
	    $componentConfig->setId( "nomencladorfilter" );
		$componentConfig->setType( "NomencladorFilter" );
		
		//TODO esto setearlo en el .rasty
	    $this->filter = ComponentFactory::buildByType($componentConfig, $this);
	    
    	$filter = new UINomencladorCriteria();
		return $filter;    	
    	    	
    }
    
    
	protected function initModel() {

		$this->setHasCheckboxes( false );
		
		$column = GridModelBuilder::buildColumn( "codigo", "nomenclador.codigo", 30 ) ;
		$this->addColumn( $column );

		$column = GridModelBuilder::buildColumn( "nombre", "nomenclador.nombre", 30 ) ;
		$this->addColumn( $column );

	}

	public function getDefaultFilterField() {
        return "nombre";
    }

	public function getDefaultOrderField(){
		return "nombre";
	}    
	
	public function getEntityId( $anObject ){
		return $anObject->getCodigo();
	}
	
	/**
	 * opciones de menú dado el item
	 * @param unknown_type $item
	 */
	public function getMenuGroups( $item ){
	
		//FIXME seguir según NoticiaGridModel.
		//pasar css y js.
		
		$group = new MenuGroup();
		$group->setLabel("grupo");
		$options = array();
		
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "menu.nomenclador.modificar") );
		$menuOption->setPageName( "NomencladorModificar" );
		$menuOption->addParam("oid",$item->getOid());
		$menuOption->setImageSource( $this->getWebPath() . "css/images/editar_32.png" );
		$options[] = $menuOption ;
		
		$group->setMenuOptions( $options );
		
		return array( $group );
		
	}
}
?>