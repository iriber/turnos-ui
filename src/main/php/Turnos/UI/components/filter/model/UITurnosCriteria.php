<?php
namespace Turnos\UI\components\filter\model;

use Rasty\Grid\filter\model\UICriteria;
use Rasty\utils\RastyUtils;

/**
 * Representa un criterio de b�squeda.
 * 
 * @author bernardo
 *
 */
abstract class UITurnosCriteria extends UICriteria{

	/**
	 * @var Criteria
	 */
	protected abstract function newCoreCriteria();
	
	public function buildCoreCriteria(){
		
		$criteria = $this->newCoreCriteria();
				
		$criteria->setOrders( $this->getOrders() );
		
		//paginación.
		$criteria->setMaxResult( $this->getRowPerPage() );
		
		$offset = (($this->getPage()-1) * $this->getRowPerPage() ) ;
		$criteria->setOffset( $offset );
		
		return $criteria;
	}


}