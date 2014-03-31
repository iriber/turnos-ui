<?php
namespace Turnos\UI\pages\clientes;

use Turnos\UI\service\UIServiceFactory;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\components\filter\model\UIClienteCriteria;

use Turnos\UI\components\grid\model\ClienteGridModel;

use Turnos\UI\service\UIClienteService;

use Turnos\UI\utils\TurnosUtils;

use Rasty\utils\XTemplate;
use Rasty\utils\RastyUtils;
use Rasty\i18n\Locale;

use Turnos\Core\model\Cliente;
use Turnos\Core\criteria\ClienteCriteria;
use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;


/**
 * Página para exportar los clientes en pdf.
 * 
 * @author bernardo
 * @since 27/03/2014
 * 
 * @Rasty\conf\annotations\Page (name="Clientes", 
 * 								 location="pages/clientes/Clientes.page" , 
 * 								 url="pacientes")
 */
class ClientesExportar extends TurnosPage{

	
	public function __construct(){
		
	}
	
	public function getTitle(){
		return $this->localize( "clientes.title" );
	}

	public function getMenuGroups(){

		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();
		
//		$menuOption = new MenuOption();
//		$menuOption->setLabel( $this->localize( "cliente.agregar") );
//		$menuOption->setPageName("ClienteAgregar");
//		$menuOption->setImageSource( $this->getWebPath() . "css/images/clientes_48.png" );
//		$menuGroup->addMenuOption( $menuOption );
		
		
		return array($menuGroup);
	}
	
	public function getType(){
		
		return "ClientesExportar";
		
	}	

	public function getModelClazz(){
		return get_class( new ClienteGridModel() );
	}

	public function getUicriteriaClazz(){
		return get_class( new UIClienteCriteria() );
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		$criteria = new UIClienteCriteria();
		$criteria->setRowPerPage( null );
		$criteria->addOrder("nombre", "ASC");
		$clientes = UIServiceFactory::getUIClienteService()->getEntities( $criteria );

		$xtpl->assign("lbl_nombre", $this->localize("cliente.nombre") );
		$xtpl->assign("lbl_nroHistoriaClinica", $this->localize("cliente.nroHistoriaClinica") );
		
		foreach ($clientes as $cliente) {
			$xtpl->assign("nroHistoriaClinica", $cliente->getNroHistoriaClinica() );
			$xtpl->assign("nombre", $cliente->getNombre() );
			$xtpl->parse("main.row");
		}
		
	}

}
?>