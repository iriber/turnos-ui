<?php
namespace Turnos\UI\actions\apijson;

use Turnos\UI\service\UIServiceFactory;
use Turnos\UI\utils\TurnosUtils;
use Turnos\Core\model\Turno;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Cliente;
use Turnos\Core\model\Practica;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;
use Rasty\utils\Logger;

use Rasty\security\RastySecurityContext;

use Rasty\i18n\Locale;
use Rasty\factory\PageFactory;

use Rasty\app\RastyMapHelper;
use Rasty\factory\ComponentFactory;
use Rasty\factory\ComponentConfig;

use Rasty\exception\UserRequiredException;
/**
 * action genérica para la api json
 * @author bernardo
 * @since 15/05/2014
 */
abstract class TurnosApiJson extends JsonAction{


	public function execute(){

		try {

			if( !TurnosUtils::isProfesionalLogged() ){
				throw new UserRequiredException(); 
			}
			
			$response = $this->executeImpl();
			
		} catch (RastyException $e) {
		
			$response["error"] = $e->getMessage();
			$response["errorCode"]= $e->getCode();
		}
		
		return $response;
	}	
	
	protected abstract function executeImpl();
	
	protected function buildUserJson($user){
		
		return array( "username" => $user->getUsername(),
						"password" => $user->getPassword(),
						"name" => $user->getName()  
					);
		
	}
	
	protected function buildProfesionalJson($profesional){
		
		return array( "nombre" => $profesional->getNombre(),
						"oid" => $profesional->getOid()
					);
		
	}
	

	protected function buildEstadoJson($estado){
		
		return array( "label" => self::localize(EstadoTurno::getLabel($estado)),
						"css" => TurnosUtils::getEstadoTurnoCss($estado),
						"oid" => $estado  
					);
		
	}
	
	protected function buildTurnoJson(Turno $turno){

		$cliente = $turno->getCliente();
		if(!empty($cliente) && $cliente->getOid()>0){
			$clienteNombre = $cliente->__toString() ;
			$clienteOid = $cliente->getOid();
			$clienteHc = $cliente->getNroHistoriaClinica();

			$telefonos = array();
			$telFijo = $turno->getCliente()->getTelefonoFijo();
			if(!empty($telFijo))
				$telefonos[] = $telFijo;	
				
			$telMovil = $turno->getCliente()->getTelefonoMovil();
			if(!empty($telMovil))
				$telefonos[] = $telMovil;	
				
			$clienteTelefono = implode(" / ", $telefonos);
			
		}else{
			$clienteNombre = $turno->getNombre();
			$clienteTelefono = $turno->getTelefono();
			$clienteOid = null;
			$clienteHc = null;
			
		}
		
		$os = $turno->getObraSocial();
		if($os!=null) {
			$osOid = $os->getOid();
			$osNombre = $os->getNombre();
		}else{
			$osOid = null;
			$osNombre = null;
		}
		$importe = $turno->getImporte();

		$turno = array(
			"hora" =>  TurnosUtils::formatTimeToView($turno->getHora()),
			"estado" => $turno->getEstado(),
			"observaciones" => $turno->getObservaciones(),
			"importe" => TurnosUtils::formatMontoToView($importe),
			"turnoCss" => TurnosUtils::getEstadoTurnoCss($turno->getEstado()),
			"cliente" => array(
					"nombre" => $clienteNombre,
					"telefono" => $clienteTelefono,
					"oid" => $clienteOid,				
					"nroHistoriaClinica" => $clienteHc),
			"obraSocial" => array(
					"oid" => $osOid,
					"nombre" => $osNombre)
		);
		
		return $turno;
	}
	
		protected function buildPracticaJson(Practica $practica){

		$fecha = TurnosUtils::formatDateToView($practica->getFecha()) ;
		$nomencladorCodigo = $practica->getNomenclador()->getCodigo(); 
		$nomencladorNombre = $practica->getNomenclador()->getNombre(); 
		
		$osNombre = "";
		$osOid = null;
		$os = $practica->getObraSocial();
		if(!empty($os)){
			$osNombre = $os->getNombre();
			$osOid = $os->getOid();
		}
		$pos = $practica->getPlanObraSocial();
		if(!empty($pos))
			$osNombre .= " " . $pos->getNombre();
		
		$osNombre .= "  " . $practica->getNroObraSocial();
				
		$observaciones = str_replace("\n", "<br/>", $practica->getObservaciones());
					
		$p = $practica->getProfesional();
		if(!empty($p) && $p!=null){ 
			$profesionalNombre = $p->getNombre();
			$profesionalOid = $p->getOid();
		}else{
			$profesionalNombre= "";
			$profesionalOid = null;
		}
			
		$practica = array(
			"oid" => $practica->getOid(),
			"fecha" => $fecha,
			"observaciones" => $observaciones,
			"obraSocial" => array(
					"oid" =>  $osOid,
					"nombre" => $osNombre
							),
			"nomenclador" => array( 
					"codigo" => $nomencladorCodigo,
					"nombre" => $nomencladorNombre ),
			
			"profesional" => array(
					"nombre" => $profesionalNombre,
					"oid" => $profesionalOid)
		);
		
		return $practica;
	}
	
	protected function buildClienteJson(Cliente $cliente){

		if(!empty($cliente) && $cliente->getOid()>0){
			$clienteNombre = $cliente->__toString() ;
			$clienteOid = $cliente->getOid();
			$clienteHc = $cliente->getNroHistoriaClinica();

			$telefonos = array();
			$telFijo = $cliente->getTelefonoFijo();
			if(!empty($telFijo))
				$telefonos[] = $telFijo;	
				
			$telMovil = $cliente->getTelefonoMovil();
			if(!empty($telMovil))
				$telefonos[] = $telMovil;	
				
			$clienteTelefono = implode(" / ", $telefonos);
			
		}
		
		return array(
					"nombre" => $clienteNombre,
					"telefono" => $clienteTelefono,
					"oid" => $clienteOid,				
					"nroHistoriaClinica" => $clienteHc);
				
	}
}
?>