<?php
namespace Turnos\UI\pages\clientes\agregar;

use Turnos\UI\pages\TurnosPage;

use Rasty\utils\XTemplate;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\ObraSocial;
use Rasty\Menu\menu\model\MenuGroup;
use Rasty\Menu\menu\model\MenuOption;

class ClienteAgregar extends TurnosPage{

	/**
	 * cliente a agregar.
	 * @var Cliente
	 */
	private $cliente;

	
	public function __construct(){
		
		//inicializamos el cliente.
		$cliente = new Cliente();
		$cliente->setObraSocial( new ObraSocial() );
		//$cliente->setNombre("Bernardo");
		//$cliente->setEmail("ber@mail.com");
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
	
	public function getTitle(){
		return $this->localize( "cliente.agregar.title" );
	}

	public function getType(){
		
		return "ClienteAgregar";
		
	}	

	protected function parseXTemplate(XTemplate $xtpl){
		
		
	}


	public function getCliente()
	{
	    return $this->cliente;
	}

	public function setCliente($cliente)
	{
	    $this->cliente = $cliente;
	}
	
	
					
	public function getMsgError(){
		return "";
	}
}
?>