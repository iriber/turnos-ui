<?php
namespace Turnos\UI\components\grid\model;

use Turnos\UI\components\filter\model\UIPracticaCriteria;

use Rasty\Grid\entitygrid\EntityGrid;
use Rasty\Grid\entitygrid\model\EntityGridModel;
use Rasty\Grid\entitygrid\model\GridModelBuilder;
use Rasty\Grid\filter\model\UICriteria;

use Turnos\UI\service\UIServiceFactory;
use Rasty\utils\RastyUtils;
use Rasty\utils\Logger;

use Rasty\Menu\menu\model\MenuOption;
use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuActionOption;
use Rasty\Menu\menu\model\MenuActionAjaxOption;

/**
 * Model para la grilla de practicas.
 * @author bernardo
 * @since 09/03/2014
 */
class PracticaGridModel extends EntityGridModel{

	public function __construct() {

        parent::__construct();
        $this->initModel();
        
    }
    
    public function getService(){
    	
    	return UIServiceFactory::getUIPracticaService();
    }
    
    public function getFilter(){
    	
    	$componentConfig = new ComponentConfig();
	    $componentConfig->setId( "practicafilter" );
		$componentConfig->setType( "PracticaFilter" );
		
		//TODO esto setearlo en el .rasty
	    $this->filter = ComponentFactory::buildByType($componentConfig, $this);
	    
    	$filter = new UIPracticaCriteria();
		return $filter;    	
    }
        
	protected function initModel() {

		$this->setHasCheckboxes( false );
		
		$column = GridModelBuilder::buildColumn( "oid", "practica.oid", 20, EntityGrid::TEXT_ALIGN_RIGHT );
		$this->addColumn( $column );
		//$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );
		 
		$column = GridModelBuilder::buildColumn( "fecha", "practica.fecha", 20, EntityGrid::TEXT_ALIGN_CENTER );
		$this->addColumn( $column );
		
	}

	public function getDefaultFilterField() {
        return "fecha";
    }

	public function getDefaultOrderField(){
		return "fecha";
	}    
    /**
     * (non-PHPdoc)
     * @see GridModel::getTitle();
     */
    public function getTitle() {
        return TRN_MSG_CLIENTE_TITLE_LIST;
    }

 	/**
     * (non-PHPdoc)
     * @see GridModel::getRowActionsModel( $item );
     */
    public function getRowActionsModel($item) {

        return $this->getDefaultRowActions($item, "practica", "practica", true, true, true, false, 500, 750);
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
		$menuOption->setLabel( $this->localize( "menu.practica.modificar") );
		$menuOption->setPageName( "PracticaModificar" );
		$menuOption->addParam("oid",$item->getOid());
		$menuOption->setImageSource( $this->getWebPath() . "css/images/editar_32.png" );
		$options[] = $menuOption ;
		
		
		/*
		$menuOption = new MenuActionAjaxOption();
		$menuOption->setLabel( $this->localize( "menu.practica.eliminar") );
		$menuOption->setActionName( "EliminarPractica" );
		$menuOption->setConfirmMessage( $this->localize( "practica.eliminar.confirm.msg") );
		$menuOption->setConfirmTitle( $this->localize( "practica.eliminar.confirm.title") );
		$menuOption->setOnSuccessCallback( "eliminarCallback" );
		$menuOption->addParam("oid",$item->getOid());
		$menuOption->setImageSource( $this->getWebPath() . "css/images/eliminar_32.png" );
		$options[] = $menuOption ;
		*/
		$group->setMenuOptions( $options );
		
		return array( $group );
		
	} 
    
}
?>