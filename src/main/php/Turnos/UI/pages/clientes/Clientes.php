<?php
namespace Turnos\UI\pages\clientes;

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

class Clientes extends TurnosPage{

	
	public function __construct(){
		
	}
	
	public function getTitle(){
		return $this->localize( "clientes.title" );
	}

	public function getMenuGroups(){

		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "cliente.agregar") );
		$menuOption->setPageName("ClienteAgregar");
		$menuOption->setImageSource( $this->getWebPath() . "css/images/clientes_48.png" );
		$menuGroup->addMenuOption( $menuOption );
		
		
		return array($menuGroup);
	}
	
	public function getType(){
		
		return "Clientes";
		
	}	

	public function getModelClazz(){
		return get_class( new ClienteGridModel() );
	}

	public function getUicriteriaClazz(){
		return get_class( new UIClienteCriteria() );
	}
	
	protected function parseXTemplate(XTemplate $xtpl){

		$xtpl->assign("legend_operaciones", $this->localize("grid.operaciones") );
		$xtpl->assign("legend_resultados", $this->localize("grid.resultados") );
		
		$xtpl->assign("agregar_label", $this->localize("cliente.agregar") );
	}

}
?>