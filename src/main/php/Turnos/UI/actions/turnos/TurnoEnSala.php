<?php
namespace Turnos\UI\actions\turnos;

/**
 * se cambia el estado a EnSala de un turno.
 * 
 * puede que aún no tenga cliente asociado por lo que retornará error
 * con el link para ingresar los datos del cliente.
 * 
 * @author bernardo
 * @since 23/08/2013
 */
use Turnos\UI\service\UIServiceFactory;
use Turnos\Core\exception\TurnoClienteRequiredException;

use Rasty\i18n\Locale;
use Rasty\utils\RastyUtils;
use Rasty\exception\RastyException;
use Rasty\actions\JsonAction;
use Rasty\utils\LinkBuilder;

class TurnoEnSala extends JsonAction{

	
	public function execute(){

		$result = array();
		
		$oid = RastyUtils::getParamGET("oid");
		
		try {
			
			UIServiceFactory::getUITurnoService()->enSala( $oid );
			
			$result["info"] = Locale::localize("operation.successful");
			
		} catch ( TurnoClienteRequiredException $e){
			
			//el error indica falta registrar al cliente.
			//sólo tenemos el nombre y el teléfono.
			$turno = UIServiceFactory::getUITurnoService()->get( $oid );
			$oid = $turno->getOid();
			$nombre = $turno->getNombre();
			$telefono = $turno->getTelefono();
			//indicamos el link para ir a registrar el cliente del turno.
			$link = LinkBuilder::getPageUrl( "TurnoModificar", array("oid"=>$oid)) ;
			$result["link"] = $link;
			$result["title"] = Locale::localize( "turno.enSala.cliente.required.title" );
			$message = Locale::localize( "turno.enSala.cliente.required.msg" );
			$result["msg"] = $message;
			$result["nombre"] = $nombre;
			$result["telefono"] = $telefono;
			$result["turnoOid"] = $oid;
			$result["error"] = Locale::localize( $e->getMessage() ) ;
			
		} catch (RastyException $e) {
		
			$msg = $e->getMessage();
			if(empty($msg) )
				$msg = "operation.fails";

			$result["error"] = Locale::localize( $msg ) ;
			
		}
		
		return $result;
		
	}
}
?>