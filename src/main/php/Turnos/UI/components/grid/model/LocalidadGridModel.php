<?php
namespace Turnos\UI\components\grid\model;

use Turnos\UI\components\filter\model\UILocalidadCriteria;

use Rasty\Grid\entitygrid\EntityGrid;
use Rasty\Grid\entitygrid\model\EntityGridModel;
use Rasty\Grid\entitygrid\model\GridModelBuilder;
use Rasty\Grid\filter\model\UICriteria;

use Turnos\UI\service\UIServiceFactory;
use Rasty\utils\RastyUtils;
use Rasty\utils\Logger;

/**
 * Model para la grilla de localidades.
 * @author bernardo
 * @since 13/08/2013
 */
class LocalidadGridModel extends EntityGridModel{

	public function __construct() {

        parent::__construct();
        $this->initModel();
        
    }
    
    public function getService(){
    	
    	return UIServiceFactory::getUILocalidadService();
    }
    
    public function getFilter(){
    	
    	$criteria = new UILocalidadCriteria();
		return $criteria;    	
    }
    
    
	protected function initModel() {

		$this->setHasCheckboxes( false );
		
		$column = GridModelBuilder::buildColumn( "oid", "localidad.oid", 20, EntityGrid::TEXT_ALIGN_RIGHT );
		$this->addColumn( $column );
		$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );
		 
		$column = GridModelBuilder::buildColumn( "nombre", "localidad.nombre", 30 ) ;
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
	
}
?>