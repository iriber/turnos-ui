<?php

namespace Turnos\UI\layouts;

use Rasty\Layout\layout\RastyLayout;

use Rasty\utils\XTemplate;
use Rasty\conf\RastyConfig;

class TurnosLayout extends RastyLayout{

	private $menuGroups;
	
	public function getContent(){
		
		
		$header = $this->getComponentById("header");
		
		if(!empty($header))
			$header->setTitle( $this->getPage()->getTitle() );
			
		//contenido del componente..
				
		$xtpl = $this->getXTemplate( dirname(__DIR__) . "/layouts/TurnosLayout.htm" );
		$xtpl->assign('WEB_PATH', RastyConfig::getInstance()->getWebPath() );
		
		$this->parseMetaTags($xtpl);
        $this->parseStyles($xtpl);
        $this->parseScripts($xtpl);
        $this->parseLinks($xtpl);
        
        $this->parseErrors($xtpl);
        
		
		$xtpl->assign('title', $this->oPage->getTitle());
		
		$xtpl->assign('page',   $this->oPage->render() );

		//chequeamos si hay que mostrar errores.
		
		
		$xtpl->parse("main");
		$content = $xtpl->text("main");
		
		/*
		echo "<pre>";
		var_dump($xtpl);
		echo "</pre>";
		*/
		
		return $content;
	}
	
	
	public function getType(){
		
		return "TurnosLayout";
		
	}	


	protected function initStyles(){
		parent::initStyles();
		
		$webPath = RastyConfig::getInstance()->getWebPath();
		
		$this->addStyle( "$webPath/css/jquery/jquery-ui-1.7.2.custom.css");
    	$this->addStyle( "$webPath/css/jquery/jquery.alerts.css");
    	$this->addStyle( "$webPath/css/jquery/jquery.ui.core.css");
    	$this->addStyle( "$webPath/css/jquery/jVal.css");
    	
    	$this->addStyle( "$webPath/css/soft/styles.css");
    	
    	$this->addStyle( "$webPath/css/rich_grid.css");

    	//$this->addStyle( PHPRASTY_GRID_PATH . "/entitygrid/css/grid.css");
    	
    	//$this->addStyle( "$webPath/css/jquerymobile/jquery.mobile-1.3.1.min.css");
    	//$this->addStyle( "$webPath/css/jquerymobile/jquery.mobile.theme-1.3.1.min.css");
		//$this->addStyle( "$webPath/css/jquerymobile/jquery.mobile.structure-1.3.1.min.css");
		
    	$this->addStyle( "$webPath/css/fancybox/jquery.fancybox.css?v=2.1.4" );
    	
    	$this->addStyle( "$webPath/css/turnos.css");
		
		

	}
	
	protected function initScripts(){
		parent::initScripts();
		
		$webPath = RastyConfig::getInstance()->getWebPath();
		
		//$this->addScript( "$webPath/js/form/form.js" );
		//$this->addScript( "$webPath/js/findobject/findobject.js" );
    	
		$this->addScript(  "$webPath/js/jquery/jquery-1.7.min.js" );;
    	$this->addScript(  "$webPath/js/jquery/jquery-ui-1.7.2.custom.min.js" );
    	$this->addScript(  "$webPath/js/jquery/jquery.feedback-1.2.0.js" );
    	$this->addScript(  "$webPath/js/jquery/jquery.alerts.js" );
    	$this->addScript(  "$webPath/js/jquery/jquery.json-2.4.js" );
    	$this->addScript(  "$webPath/js/jquery/jquery.ui.datepicker-es.js" );
    	$this->addScript(  "$webPath/js/jquery/jquery-ui-timepicker-addon.js" );
    	$this->addScript(  "$webPath/js/jquery/jVal.js" );
    	$this->addScript(  "$webPath/js/jquery/jquery.form.js" );
    	
    	$this->addScript(  "$webPath/js/jquery/jquery.jqEasyCharCounter.min.js" );
    	$this->addScript(  "$webPath/js/jquery/interface.js" );
    	
		$this->addScript( "$webPath/js/jquery/jquery.tablednd.0.7.min.js");
		$this->addScript("$webPath/js/jquery/jquery.tablescroll.js");
		
		//$this->addScript("$webPath/js/jquerymobile/jquery.mobile-1.3.1.min.js");
		
		$this->addScript( "$webPath/js/jquery/jquery.mousewheel-3.0.6.pack.js" );
    	$this->addScript("$webPath/js/fancybox/jquery.fancybox.js?v=2.1.4");
    	
		$this->addScript("$webPath/js/rasty_observer.js");
		$this->addScript("$webPath/js/historiaAyuda.js");
		$this->addScript("$webPath/js/app_observer.js");
		$this->addScript("$webPath/js/turnos.js");

		//$this->addScript("$webPath/js/soft.js");
	}
	
	protected function initLinks(){
		parent::initLinks();
	}
	


	protected function parseMetaTags($xtpl) {

		$xtpl->assign('http_equiv', 'X-UA-Compatible');
        $xtpl->assign('meta_content', 'IE=7');
        $xtpl->parse('main.meta_tag');

        $xtpl->assign('http_equiv', 'Content-Type');
        $xtpl->parse('main.meta_tag');
        
        $xtpl->assign('name', 'viewport');
        $xtpl->assign('meta_content', 'width=device-width, initial-scale=1.0');
        $xtpl->assign('http_equiv', '');
        $xtpl->parse('main.meta_tag');
        
    }

    protected function parseStyles($xtpl) {

    	foreach ($this->getStyles() as $style) {
			$xtpl->assign('css', $style);
        	$xtpl->parse('main.style');			
		}
    }
	
	public function parseLinks(XTemplate $xtpl){
		
    	foreach ($this->getLinks() as $link) {
			$xtpl->assign('rel', $link["rel"]);
			$xtpl->assign('href', $link["href"]);
			$xtpl->assign('type', $link["type"]);
    		$xtpl->parse('main.link');			
		}
    }
    
	protected function parseScripts($xtpl) {

		foreach ($this->getScripts() as $script) {
			$xtpl->assign('js', $script);
        	$xtpl->parse('main.script');			
		}

    }

	protected function parseErrors($xtpl) {

		//chequemos los errores en el forward del page.
		$msg = $this->oPage->getMsgError();
		
		if( !empty($msg) ){
			
			$xtpl->assign("msg", $msg);
			//$xtpl->assign("msg",  );
			$xtpl->parse("main.msg_error" );
		}			
		

    }
    
	public function getMenuGroups()
	{
	    return $this->menuGroups;
	}

	public function setMenuGroups($menuGroups)
	{
	    $this->menuGroups = $menuGroups;
	}
}
?>