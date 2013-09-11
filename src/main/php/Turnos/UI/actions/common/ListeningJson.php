<?php
namespace Turnos\UI\actions\common;

use Rasty\actions\JsonAction;
use Rasty\utils\RastyUtils;
use Rasty\utils\ReflectionUtils;
use Rasty\exception\RastyException;

use Rasty\i18n\Locale;

/**
 * test listening
 * 
 * @author bernardo
 * @since 22/08/2013
 */
class ListeningJson extends JsonAction{

	
	public function execute(){

		$time = time();
		
		while((time() - $time) < 30) {
		    // query memcache, database, etc. for new data
		 	$data = "" ;//array("test"=>"hola");
		 	
		    // if we have new data return it
		    if(!empty($data)) {
		        return $data;
		        break;
		    }
		 
		    usleep(25000);
		}
		
	}

}
?>