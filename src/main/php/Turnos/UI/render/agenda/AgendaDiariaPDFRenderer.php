<?php

namespace Turnos\UI\render\agenda;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\render\TurnosPDFRenderer;
use Turnos\Core\model\TipoDocumento;
use Turnos\Core\model\Turno;

use Rasty\conf\RastyConfig;
use Rasty\utils\RastyUtils;

use Rasty\components\AbstractComponent;

class AgendaDiariaPDFRenderer extends TurnosPDFRenderer{

	
	
	public function __construct(){
		
		parent::__construct("L");

		
	}

	public function setComponent($component)
	{
	    parent::setComponent( $component );
	    $component->initParams();
	}
	/**
	 */	 
	protected function renderCustom(AbstractComponent $component){

		
		$profesional = $this->getComponent()->getProfesional();
		
		/* turnos del profesional */
		$this->renderTurnos();
				
		
	}
	
	protected function renderTurno(Turno $turno, $nroFila){
		
//		if($nroFila%2==0)
//			$this->SetFillColor(0,0,0);
//		else 
//			$this->SetFillColor(192,192,192);
		
		$maxWidth = $this->getMaxWidth();
		
		$hora = TurnosUtils::formatTimeToView( $turno->getHora() );
		$observaciones = $turno->getObservaciones();
		$clienteNombre = $turno->getNombre();
		$clienteTelefono = $turno->getTelefono();
		$clienteHC = "";
		$cliente = $turno->getCliente();
		if(!empty($cliente) && $cliente->getOid()>0){
			$clienteNombre = $turno->getCliente()->__toString();
			$telefonos = array();
			$telFijo = $cliente->getTelefonoFijo();
			if(!empty($telFijo))
				$telefonos[] = $telFijo;	
					
			$telMovil = $cliente->getTelefonoMovil();
			if(!empty($telMovil))
				$telefonos[] = $telMovil;	
					
			$clienteTelefono = implode(" / ", $telefonos);
			
			$clienteHC = $this->localize("cliente.nroHistoriaClinica") . " " . $cliente->getNroHistoriaClinica();
		}	
		
		$fillColors = $this->getFillColor($nroFila);
		$this->initFontLabel();
		$this->SetFillColor( $fillColors[0], $fillColors[1], $fillColors[2] );
		$this->Cell( 30 , 5 , $this->encodeCharactersPDF( $hora ) , 1 , 0 , "L",true );
		
		$infoAdicional=array();
		
		if(!empty($clienteNombre))
			$infoAdicional[] = $clienteNombre;
		if(!empty($clienteHC))
			$infoAdicional[] = $clienteHC;
		if(!empty($clienteTelefono))
			$infoAdicional[] = $clienteTelefono;
		if(!empty($observaciones))
			$infoAdicional[] = $observaciones;
		
		$this->initFontValue();
		$this->SetFillColor( $fillColors[0], $fillColors[1], $fillColors[2] );
		$this->Cell( $maxWidth-30 , 5 , $this->encodeCharactersPDF( implode(" / ", $infoAdicional) ) , 1 , 0 , "L", true );
	}
	
	protected function renderTurnos(){
		
		$turnos = $this->getComponent()->getTurnos();
		
		$cantidad = count( $turnos );
		
		$fecha = TurnosUtils::formatDateToView( $this->getComponent()->getFecha() );
		
		$this->initFontTitle();
		$titulo = RastyUtils::formatMessage($this->localize("agenda.pdf.fecha.subtitulo"), array($fecha));
		$this->Cell( 100 , 5 ,  $this->encodeCharactersPDF( $titulo ), 0 , 0 , "L" );
		$this->Ln(5);
		
		$this->initFontSubtitle();
		$total = RastyUtils::formatMessage($this->localize("agenda.pdf.total.subtitulo"), array($cantidad));
		$this->Cell( 100 , 5 ,  $this->encodeCharactersPDF( $total), 0 , 0 , "L" );
		$this->Ln(5);
		$this->Ln(5);
		
		$nroFila = 1;
		
		foreach ($turnos as $turno) {

			$this->renderTurno($turno, $nroFila++);			
			
			$this->Ln(5);
			
		}
		
	}
		
		/**
	 * (non-PHPdoc)
	 */
	function Header(){
		
		$webPath = RastyConfig::getInstance()->getWebPath();
		
		/* nombre de la clínica */
		$maxWidth = $this->getMaxWidth();
		$y_comienzo = $this->tMargin;
		$this->y = $y_comienzo;
		$this->initFontTitle();
		$profesional = $this->getComponent()->getProfesional();
		$titulo = RastyUtils::formatMessage($this->localize("agenda.pdf.titulo"), array( $profesional->getNombre() ));
		$this->Cell( 30 , 5 , $titulo , 0 , 0 , "L" );
				
		/* linea fin header */
		//$this->y = $this->tMargin;
		$this->Ln(5);
		$x_from = $this->lMargin;
		$x_to = $x_from + $maxWidth;
		$this->Line($x_from, $this->y, $x_to, $this->y);
		$this->Ln(5);
	}

	protected function renderLabelValue($label, $value, $anchoColumnaLabel=30, $anchoColumnaValue=50){
		
		$this->initFontLabel();
		$this->Cell( $anchoColumnaLabel , 5 , $label , 1 , 0 , "L" );
		$this->initFontValue();
		$this->Cell( $anchoColumnaValue , 5 , $value , 1 , 0 , "L" );
		
	}
}
?>