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

use Rasty\security\RastySecurityContext;

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
	public static function initialize( $appName = "turnos_ui"){
		
		//inicializamos la sesión.
		session_set_cookie_params(0, $appName );
		session_start ();
		if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
			RastySecurityContext::logout();
		}
		$_SESSION['LAST_ACTIVITY'] = time();
		
		
		//configuramos php
		self::configurePhp();
		
		//turnos core
		self::initializeCore();
		
		//turnos ui
		self::initializeUI( $appName );
		
		
		
	}
	
	/**
	 * Configuraciones para php
	 */
	private static function configurePhp(){
		
		//algunos configuraciones para php.
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '0');
		ini_set('display_errors', '1');
		
		include_once dirname(__DIR__) . "/conf/errorHandler.php";
	}
	
	/**
	 * Inicializamos turnos core.
	 */
	private static function initializeCore(){

		TurnosConfig::getInstance()->initialize();
		TurnosConfig::getInstance()->initLogger(dirname(__DIR__) . "/conf/log4php.xml");
		
	}
	
	
	/**
	 * Inicializamos turnos ui + Rasty
	 */
	private static function initializeUI( $appName ){
		
		$appHome = $_SERVER["DOCUMENT_ROOT"];
		
		//inicializamos rasty indicando el home de la up y el nombre
		RastyConfig::getInstance()->initialize($appHome, $appName);
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