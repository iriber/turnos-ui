<?php

namespace Turnos\UI\render;

use Turnos\UI\utils\TurnosUtils;

use Rasty\conf\RastyConfig;

use Rasty\render\PDFRenderer;
use Rasty\components\AbstractComponent;

abstract class TurnosPDFRenderer extends PDFRenderer{

	
	public function __construct(){
		
		parent::__construct();
		
	}

	
	protected function renderContent(AbstractComponent $component){

		
		//TODO header.
		
		$this->renderCustom($component);
		
		//TODO footer.
		
	}
	
	protected abstract function renderCustom(AbstractComponent $component);
	
	
	/**
	 * (non-PHPdoc)
	 */
	function Header(){
		
		$webPath = RastyConfig::getInstance()->getWebPath();
		
		/* logo Turnos */
		//imagen del instituto.
//		$maxWidth = ($this->w) - $this->lMargin - $this->rMargin;
//		$y_comienzo = $this->tMargin;
//		$this->y = $y_comienzo;
//		$this->Image( $webPath . 'css/images/logo_right.png', $this->rMargin+2, $this->y + 2);
//		
//		$this->Ln(5);
		
		/* nombre de la clínica */
		$maxWidth = $this->getMaxWidth();
		$y_comienzo = $this->tMargin;
		$this->y = $y_comienzo;
		$this->initFontTitle();
		$this->Cell( 30 , 5 , $this->localize("app.title") , 0 , 0 , "L" );
				
		/* fecha */
		$this->y = $this->tMargin;
		$this->x= $maxWidth - 30 + $this->lMargin;
		$this->initFontValue();
		$this->Cell( 30 , 5 , TurnosUtils::formatDateToView( new \DateTime()) , 0 , 0 , "R" );
		$this->Ln(5);
		
		/* linea fin header */
		//$this->y = $this->tMargin;
		$x_from = $this->lMargin;
		$x_to = $x_from + $maxWidth;
		$this->Line($x_from, $this->y, $x_to, $this->y);
		$this->Ln(5);
	}
	
	/**
	 * (non-PHPdoc)
	 */
	function Footer(){
		//Posici�n: a 1,5 cm del final
		$this->SetY(-15);
		
		$x_from = $this->lMargin;
		$x_to = $x_from + $this->getMaxWidth();
		$this->SetDrawColor(192,192,192);
		$this->Line($x_from, $this->y, $x_to, $this->y);
		
		//Arial italic 8
		$this->SetFont('Arial','I',8);
		//t�tulo del listado
		$this->Cell(10,10, $this->localize("app.pdf.footer") ,0,0,'L');
		
		
		//$this->Cell(0,10, $this->lblPage.' '.$this->PageNo() ,0,0,'C');
		//N�mero de p�gina
		$this->Cell(0,10, $this->PageNo() ,0,0,'R');
	}
	
	function initFontTitle(){
		$this->SetFillColor(218,218,218);
		$this->SetTextColor(1,77,137);
		$this->SetDrawColor(192,192,192);
		$this->SetLineWidth(.1);
		$this->SetFont('Arial','B',14);
	}
	
	function initFontSubtitle(){
		$this->SetFillColor(218,218,218);
		$this->SetTextColor(1,77,137);
		$this->SetLineWidth(.1);
		$this->SetFont('Arial','B',12);
	}
	
	
	function initFontLabel(){
		$this->SetFillColor(218,218,218);
		$this->SetTextColor(1,77,137);
		$this->SetLineWidth(.1);
		$this->SetFont('Arial','B',8);
	}
	
	function initFontValue(){
		$this->SetFillColor(224,235,255);
		$this->SetTextColor(0);
		$this->SetFont('Arial','',8);
	}
	
	function getMaxWidth(){
		
		return ($this->w) - $this->lMargin - $this->rMargin;
		
	}
	
	function getFillColor( $nroFila ){
		if($nroFila%2==0)
			return array (255, 255, 244);
		else 
			return array(246,250,255);
	}

}
?>