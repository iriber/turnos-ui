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
    	
    	$criteria = new UINomencladorCriteria();
		return $criteria;    	
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
}
?>