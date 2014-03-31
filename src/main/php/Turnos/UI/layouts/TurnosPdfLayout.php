<?php

namespace Turnos\UI\layouts;

use Rasty\Layout\layout\RastyLayout;

use Rasty\utils\XTemplate;
use Rasty\conf\RastyConfig;

class TurnosPdfLayout extends RastyLayout{

	
	public function getContent(){
		
		
		//$header = $this->getComponentById("header");
		
//		if(!empty($header))
//			$header->setTitle( $this->getPage()->getTitle() );
			
		//contenido del componente..
				
		$xtpl = $this->getXTemplate( dirname(__DIR__) . "/layouts/TurnosPdfLayout.htm" );
		$xtpl->assign('WEB_PATH', RastyConfig::getInstance()->getWebPath() );
		
		$this->parseMetaTags($xtpl);
        $this->parseStyles($xtpl);
        $this->parseScripts($xtpl);
        $this->parseLinks($xtpl);
        
        //$this->parseErrors($xtpl);
        
		
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
		
		return "TurnosPdfLayout";
		
	}	


	protected function initStyles(){
		parent::initStyles();
		
		$webPath = RastyConfig::getInstance()->getWebPath();
		
		
		$this->addStyle( "$webPath/css/jquery/jVal.css");
		$this->addStyle( "$webPath/metro/css/jquery/jquery.ui.all.css");
		$this->addStyle( "$webPath/metro/css/metro.css");
		$this->addStyle( "$webPath/metro/css/rasty.css");
		$this->addStyle( "$webPath/metro/css/rasty_custom.css");
		
// 		$this->addStyle( "$webPath/metro/less/metro-bootstrap.less");
		
		$this->addStyle( "$webPath/metro/css/turnos.css");

	}
	
	protected function initScripts(){
		parent::initScripts();
		
		$webPath = RastyConfig::getInstance()->getWebPath();
		
		$this->addScript( "$webPath/metro/js/jquery/jquery.min.js");
		$this->addScript( "$webPath/metro/js/jquery/jquery.widget.min.js");
		$this->addScript( "$webPath/metro/js/jquery/jquery.mousewheel.js");
		
		$this->addScript( "$webPath/metro/js/metro/metro-dropdown.js");
		$this->addScript( "$webPath/metro/js/metro/metro-input-control.js");
		$this->addScript( "$webPath/metro/js/metro/metro-core.js");
		
		$this->addScript( "$webPath/metro/js/metro/metro-button-set.js");
		$this->addScript( "$webPath/metro/js/metro/metro-dialog.js");
		//$this->addScript( "$webPath/metro/js/metro/metro-drag-title.js");
		$this->addScript( "$webPath/metro/js/metro/metro-fluentmenu.js");
		$this->addScript( "$webPath/metro/js/metro/metro-hint.js");
		$this->addScript( "$webPath/metro/js/metro/metro-listview.js");
		$this->addScript( "$webPath/metro/js/metro/metro-live-tile.js");
		$this->addScript( "$webPath/metro/js/metro/metro-notify.js");
		$this->addScript( "$webPath/metro/js/metro/metro-plugin-template.js");
		$this->addScript( "$webPath/metro/js/metro/metro-tab-control.js");
		
		$this->addScript(  "$webPath/metro/js/jquery/jquery.ui.core.js" );
		$this->addScript(  "$webPath/metro/js/jquery/jquery.ui.datepicker.js" );
		$this->addScript(  "$webPath/metro/js/jquery/jquery.ui.button.js" );
		$this->addScript(  "$webPath/metro/js/jquery/jquery.ui.dialog.js" );
		$this->addScript(  "$webPath/metro/js/jquery/jquery.ui.position.js" );
		$this->addScript(  "$webPath/metro/js/jquery/jquery.ui.resizable.js" );
		$this->addScript(  "$webPath/metro/js/jquery/jquery.ui.selectable.js" );

		$this->addScript(  "$webPath/metro/js/jquery/i18n/jquery-ui-i18n.js" );
		$this->addScript(  "$webPath/metro/js/jquery/i18n/jquery.ui.datepicker-es.js" );
		
		$this->addScript(  "$webPath/metro/js/docs.js" );
		
		$this->addScript(  "$webPath/js/jquery/jVal.js" );
    	
		$this->addScript("$webPath/js/rasty_observer.js");
		$this->addScript("$webPath/js/historiaAyuda.js");
		$this->addScript("$webPath/js/app_observer.js");
		$this->addScript("$webPath/js/rasty.js");
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

//		//chequemos los errores en el forward del page.
//		$msg = $this->oPage->getMsgError();
//		
//		if( !empty($msg) ){
//			
//			$xtpl->assign("msg", $msg);
//			//$xtpl->assign("msg",  );
//			$xtpl->parse("main.msg_error" );
//		}			
		

    }
    
}
?>