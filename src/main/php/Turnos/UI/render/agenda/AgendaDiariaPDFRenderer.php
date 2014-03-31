<?php

namespace Turnos\UI\render\agenda;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\render\TurnosPDFRenderer;
use Turnos\Core\model\TipoDocumento;

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
	
	protected function renderTurnos(){
		
		$turnos = $this->getComponent()->getTurnos();
		
		$fecha = TurnosUtils::formatDateToView( $this->getComponent()->getFecha() );
		
		$this->initFontTitle();
		$titulo = RastyUtils::formatMessage($this->localize("agenda.pdf.fecha.subtitulo"), array($fecha));
		$this->Cell( 100 , 5 ,  $this->encodeCharactersPDF( $titulo ), 0 , 0 , "L" );
		$this->Ln(5);
		$this->Ln(5);
		
		$maxWidth = $this->getMaxWidth();
		
		foreach ($turnos as $turno) {

			$hora = TurnosUtils::formatTimeToView( $turno->getHora() );
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
			
			
			$this->initFontLabel();
			$this->Cell( 30 , 5 , $this->encodeCharactersPDF( $hora ) , 1 , 0 , "L" );
			
			$this->initFontValue();
			$this->Cell( $maxWidth-30 , 5 , $this->encodeCharactersPDF( "$clienteNombre / $clienteHC / $clienteTelefono " ) , 1 , 0 , "L" );
			
			
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