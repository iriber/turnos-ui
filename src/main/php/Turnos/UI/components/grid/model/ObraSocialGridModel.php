<?php
namespace Turnos\UI\components\grid\model;


use Turnos\UI\components\filter\model\UIObraSocialCriteria;

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
 * Model para la grilla de obras sociales.
 * @author bernardo
 * @since 12/08/2013
 */
class ObraSocialGridModel extends EntityGridModel{

	public function __construct() {

        parent::__construct();
        $this->initModel();
        
    }
    
    public function getService(){
    	
    	return UIServiceFactory::getUIObraSocialService();
    }
    
    public function getFilter(){
    	
    	$componentConfig = new ComponentConfig();
	    $componentConfig->setId( "obraSocialfilter" );
		$componentConfig->setType( "ObraSocialFilter" );
		
		//TODO esto setearlo en el .rasty
	    $this->filter = ComponentFactory::buildByType($componentConfig, $this);
    	
    	$criteria = new UIObraSocialCriteria();
		return $criteria;    	
    }
    
    
	protected function initModel() {

		$this->setHasCheckboxes( false );
		
		$column = GridModelBuilder::buildColumn( "oid", "obraSocial.oid", 20, EntityGrid::TEXT_ALIGN_RIGHT );
		$this->addColumn( $column );
		$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );
		 
		$column = GridModelBuilder::buildColumn( "codigo", "obraSocial.codigo", 30 ) ;
		$this->addColumn( $column );
		$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );

		$column = GridModelBuilder::buildColumn( "nombre", "obraSocial.nombre", 30 ) ;
		$this->addColumn( $column );
		$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );

		//acciones sobre la lista
		//$this->buildAction("add_cliente_init", "add_cliente_init", "cliente.agregar", "image", "add");

	}

	public function getDefaultFilterField() {
        return "nombre";
    }

	public function getDefaultOrderField(){
		return "nombre";
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
		$menuOption->setLabel( $this->localize( "menu.obraSocial.modificar") );
		$menuOption->setPageName( "ObraSocialModificar" );
		$menuOption->addParam("oid",$item->getOid());
		$menuOption->setImageSource( $this->getWebPath() . "css/images/editar_32.png" );
		$options[] = $menuOption ;

		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "menu.obraSocial.planes") );
		$menuOption->setPageName( "PlanesObraSocial" );
		$menuOption->addParam("obraSocialOid",$item->getOid());
		$menuOption->setImageSource( $this->getWebPath() . "css/images/editar_32.png" );
		$options[] = $menuOption ;
		
		$group->setMenuOptions( $options );
		
		return array( $group );
		
	}	
}
?>