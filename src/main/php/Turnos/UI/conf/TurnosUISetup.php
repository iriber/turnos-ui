<?php
namespace Turnos\UI\conf;


use Rasty\conf\RastyConfig;
use Rasty\app\LoadRasty;
use Rasty\utils\Logger;

use Rasty\Menu\conf\RastyMenuConfig;
use Rasty\Layout\conf\RastyLayoutConfig;
use Rasty\Forms\conf\RastyFormsConfig;
use Rasty\Grid\conf\RastyGridConfig;

use Turnos\Core\conf\TurnosConfig;


/**
 * configuración turnos ui
 * 
 * @author bernardo
 * @since 06/09/2013
 */
class TurnosUISetup {

	/**
	 * inicializamos la aplicación de turnos ui
	 */
	public static function initialize(){

		//algunos configuraciones para php.
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '0');
		ini_set('display_errors', '1');
		
		/*
		 *  Inicializamos turnos core. 
		 */
		TurnosConfig::getInstance()->initialize();
		TurnosConfig::getInstance()->initLogger(dirname(__DIR__) . "/conf/log4php.xml");
		
		
		/*
		 *  Inicializar turnos ui
		 */
		
		
		//inicializamos rasty indicando el home de la up y el nombre
		RastyConfig::getInstance()->initialize("/var/www/", "turnos_ui");
		//RastyConfig::getInstance()->setWebsocketUrl("192.168.1.34");
		
		//configuramos el logger,
		Logger::configure( dirname(__DIR__) . "/conf/log4php.xml" );
		Logger::log("Logger turnos ui configurado!");
		
				
		//cargamos los componentes de turnos ui.
		$rastyLoader = LoadRasty::getInstance();
		$rastyLoader->loadXml( 
				
					dirname(__DIR__) . '/conf/rasty.xml', 
					RastyConfig::getInstance()->getAppPath() . "src/main/php/Turnos/UI/", 
					RastyConfig::getInstance()->getWebPath() 
				
				);

		//inicializamos los módulos rasty que utilizaremos
		RastyGridConfig::initialize();
		RastyLayoutConfig::initialize();
		RastyFormsConfig::initialize();;
		RastyMenuConfig::initialize();
		
	}
			
}