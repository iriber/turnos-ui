<?php
namespace Turnos\UI\components\grid\model;


use Turnos\UI\components\filter\model\UIProfesionalCriteria;

use Rasty\Grid\entitygrid\EntityGrid;
use Rasty\Grid\entitygrid\model\EntityGridModel;
use Rasty\Grid\entitygrid\model\GridModelBuilder;
use Rasty\Grid\filter\model\UICriteria;

use Turnos\UI\service\UIServiceFactory;
use Rasty\utils\RastyUtils;
use Rasty\utils\Logger;

/**
 * Model para la grilla de profesionales.
 * @author bernardo
 * @since 14/08/2013
 */
class ProfesionalGridModel extends EntityGridModel{

	public function __construct() {

        parent::__construct();
        $this->initModel();
        
    }
    
    public function getService(){
    	
    	return UIServiceFactory::getUIProfesionalService();
    }
    
    public function getFilter(){
    	
    	$criteria = new UIProfesionalCriteria();
		return $criteria;    	
    }
    
    
	protected function initModel() {

		$this->setHasCheckboxes( false );
		
		$column = GridModelBuilder::buildColumn( "oid", "profesional.oid", 20, EntityGrid::TEXT_ALIGN_RIGHT );
		$this->addColumn( $column );
		$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );
		 
		$column = GridModelBuilder::buildColumn( "codigo", "profesional.matricula", 30 ) ;
		$this->addColumn( $column );
		$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );

		$column = GridModelBuilder::buildColumn( "nombre", "profesional.nombre", 30 ) ;
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