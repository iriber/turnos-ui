<?php
namespace Turnos\UI\pages\clientes\consultar;

use Turnos\UI\pages\TurnosPage;

use Turnos\UI\service\UIServiceFactory;

use Rasty\utils\XTemplate;
use Turnos\Core\model\Cliente;
use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

class ClienteConsultar extends TurnosPage{

	/**
	 * cliente a consultar.
	 * @var Cliente
	 */
	private $cliente;

	
	public function __construct(){
		
		//inicializamos el cliente.
		$cliente = new Cliente();
		$this->setCliente($cliente);
				
	}
	
	public function getMenuGroups(){

		//TODO construirlo a partir del usuario 
		//y utilizando permisos
		
		$menuGroup = new MenuGroup();
		
		$menuOption = new MenuOption();
		$menuOption->setLabel( $this->localize( "form.volver") );
		$menuOption->setPageName("Clientes");
		$menuGroup->addMenuOption( $menuOption );
		
		
		return array($menuGroup);
	}
	public function setClienteOid($oid){
		
		//a partir del id buscamos el cliente a modificar.
		$cliente = UIServiceFactory::getUIClienteService()->get($oid);
		
		$this->setCliente($cliente);
		
	}
	
	public function getTitle(){
		return $this->localize( "cliente.consultar.title" );
	}

	public function getType(){
		
		return "ClienteConsultar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
	}

	public function getCliente(){
		
	    return $this->cliente;
	}

	public function setCliente($cliente)
	{
	    $this->cliente = $cliente;
	}
}
?>