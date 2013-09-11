<?php
namespace Turnos\UI\components\grid\model;

use Turnos\UI\components\filter\model\UIClienteCriteria;

use Rasty\Grid\entitygrid\EntityGrid;
use Rasty\Grid\entitygrid\model\EntityGridModel;
use Rasty\Grid\entitygrid\model\GridModelBuilder;
use Rasty\Grid\filter\model\UICriteria;

use Turnos\UI\service\UIServiceFactory;
use Rasty\utils\RastyUtils;
use Rasty\utils\Logger;

/**
 * Model para la grilla de clientes.
 * @author bernardo
 * @since 12/08/2013
 */
class ClienteGridModel extends EntityGridModel{

	public function __construct() {

        parent::__construct();
        $this->initModel();
        
    }
    
    public function getService(){
    	
    	return UIServiceFactory::getUIClienteService();
    }
    
    public function getFilter(){
    	
    	$criteria = new UIClienteCriteria();
		return $criteria;    	
    }
    
    
	protected function initModel() {

		$this->setHasCheckboxes( false );
		
		$column = GridModelBuilder::buildColumn( "oid", "cliente.oid", 20, EntityGrid::TEXT_ALIGN_RIGHT );
		$this->addColumn( $column );
		//$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );
		 
		$column = GridModelBuilder::buildColumn( "nombre", "cliente.nombre", 30 ) ;
		$this->addColumn( $column );
		//$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );

		/*
		$column = GridModelBuilder::buildColumn( "apellido", TRN_LBL_CLIENTE_APELLIDO, 30 );
		$this->addColumn( $column );
		$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );
		*/
		
		//$column = GridModelBuilder::buildColumn( "tipoDocumento", "cliente.tipoDocumento", 10, EntityGrid::TEXT_ALIGN_LEFT );
		//$this->addColumn( $column );
		
		$column = GridModelBuilder::buildColumn( "nroDocumento", "cliente.nroDocumento", 40, EntityGrid::TEXT_ALIGN_LEFT );
		$this->addColumn( $column );
		
		//$column = GridModelBuilder::buildColumn( "edad", "cliente.edad", 40, EntityGrid::TEXT_ALIGN_RIGHT );
		//$this->addColumn( $column );
		
		//$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );

		$column = GridModelBuilder::buildColumn( "telefonoFijo", "cliente.telefonoFijo", 40 );
		$this->addColumn( $column );
		//$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );
		
		$column = GridModelBuilder::buildColumn( "domicilio", "cliente.domicilio", 30, EntityGrid::TEXT_ALIGN_LEFT );
		$this->addColumn( $column );
		//$this->addFilter( GridModelBuilder::buildFilterModelFromColumn( $column ) );

		
		
        $column = GridModelBuilder::buildColumn( "obraSocial.nombre", "cliente.obraSocial", 40, EntityGrid::TEXT_ALIGN_LEFT );
		$this->addColumn( $column );
		$column = GridModelBuilder::buildColumn( "nroObraSocial", "cliente.nroObraSocial", 40, EntityGrid::TEXT_ALIGN_RIGHT );
		$this->addColumn( $column );
		
		//agrupamos encabezados.
		$group1 = array("tipoDocumento", "nroDocumento");
		//$this->setGroupToColumns("cliente.documento", $group1);

		$group2 = array("obraSocial.nombre", "nroObraSocial");
		//$this->setGroupToColumns("cliente.obraSocial", $group2);
		
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

        return $this->getDefaultRowActions($item, "cliente", "cliente", true, true, true, false, 500, 750);
    }
	
}
?>