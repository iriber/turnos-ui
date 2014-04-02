<?php

namespace Turnos\UI\render\historia;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\render\TurnosPDFRenderer;
use Turnos\Core\model\TipoDocumento;
use Turnos\Core\model\TipoAfiliadoObraSocial;

use Rasty\conf\RastyConfig;
use Rasty\utils\RastyUtils;

use Rasty\components\AbstractComponent;

class HistoriaClinicaPDFRenderer extends TurnosPDFRenderer{

	
	
	public function __construct(){
		
		parent::__construct("L");
		
	}

	/**
	 */	 
	protected function renderCustom(AbstractComponent $component){

		$cliente = $this->getComponent()->getCliente();
		
		/* datos personales */
		$this->renderDatosPersonales();
		
		$this->Ln(5);
		
		/* prácticas realizadas */
		$this->renderPracticas();
				
		
	}
	
	protected function renderPracticas(){
		
		$practicas = $this->getComponent()->getPracticas();
		
		$this->initFontTitle();
		$this->Cell( 30 , 5 , $this->localize("historia.pdf.practicas.subtitulo", true) , 0 , 0 , "L" );
		$this->Ln(5);
		$this->Ln(5);
		
		$maxWidth = $this->getMaxWidth();
		
		
		foreach ($practicas as $practica) {
			
//			;"practica.fecha") );
//			$xtpl->assign("profesional_lbl", $this->localize("practica.profesional") ); 
//			$xtpl->assign("obraSocial_lbl", $this->localize("practica.obraSocial")

			$fecha = TurnosUtils::formatDateToView($practica->getFecha());
			$nomencladorCodigo = $practica->getNomenclador()->getCodigo();
			$nomencladorNombre = $practica->getNomenclador()->getNombre();
			
			$y_fila = $this->y;
			
			$this->initFontLabel();
			$this->Cell( 30 , 5 , $this->encodeCharactersPDF( $fecha ) , 1 , 0 , "L" );
			
			$this->initFontValue();
			$this->Cell( 100 , 5 , $this->encodeCharactersPDF( "$nomencladorCodigo $nomencladorNombre" ) , 1 , 0 , "L" );
			$atendio = $this->localize("practica.profesional") . " " .  $practica->getProfesional() ;
			$os = $practica->getObraSocial();
			if(!empty($os)){
				$osNombre = $os->getNombre();
				$atendio .= " / $osNombre";
			}
			$this->Cell( $maxWidth-130 , 5 , $this->encodeCharactersPDF( $atendio ), 1 , 0 , "L" );
			$this->Ln(5);
			
			//($w, $h, $txt, $border=0, $align='J', $fill=false
			$this->MultiCell( $maxWidth , 5 , $this->encodeCharactersPDF( $practica->getObservaciones() ) , 1 ,  "J", 1 );
			
			$this->Ln(5);
			
			
		}
		
		
	}
	
	protected function renderDatosPersonales(){
		
		$cliente = $this->getComponent()->getCliente();
		
		$nroDoc =  $cliente->getNroDocumento();
		$tipoDoc = $this->localize( TipoDocumento::getLabel($cliente->getTipoDocumento())) ;
		$edad = TurnosUtils::formatEdad( TurnosUtils::getEdad( $cliente->getFechaNacimiento()) );
		$obraSocial = $cliente->getObraSocial();
		$nroObraSocial = $cliente->getNroObraSocial();
		if( !empty($obraSocial) )
			$osNombre = $obraSocial->getNombre();
		else 
			$osNombre = "-";
		$fechaAlta = TurnosUtils::formatDateToView($cliente->getFechaAlta());
		$fechaNacimiento = TurnosUtils::formatDateToView($cliente->getFechaNacimiento());
		$domicilio = $cliente->getDomicilio();
		
		$telefonos = array();
		$fijo = $cliente->getTelefonoFijo();
		$movil = $cliente->getTelefonoMovil();
		if(!empty($fijo))
			$telefonos[] = $fijo;
		if(!empty($movil))
			$telefonos[] = $movil;
			
		$telefonos = implode(" / ", $telefonos);
		
		
		$tipoAfiliado = $this->localize( TipoAfiliadoObraSocial::getLabel( $cliente->getTipoAfiliado() ) );
		
		//	$xtpl->assign("email" , $this->getCliente()->getEmail() );
			
			
		$anchoColumnaLabel = 30;
		$anchoColumnaValue = 60;
		
		$this->renderLabelValue($this->localize("cliente.nombre", true), $this->encodeCharactersPDF( $cliente->getNombre() ), 30, 130);
		$this->renderLabelValue($this->localize("cliente.documento", true), $this->encodeCharactersPDF( "$tipoDoc $nroDoc" ));
		$this->Ln(5);
		
		$this->renderLabelValue($this->localize("cliente.fechaNacimiento", true), $this->encodeCharactersPDF( $fechaNacimiento ));
		$this->renderLabelValue($this->localize("cliente.edad", true), $this->encodeCharactersPDF( $edad ));
		$this->renderLabelValue($this->localize("cliente.fechaAlta", true), $this->encodeCharactersPDF( $fechaAlta ));
		$this->Ln(5);
		
		$this->renderLabelValue($this->localize("cliente.obraSocial", true), $this->encodeCharactersPDF( $osNombre ));
		$this->renderLabelValue($this->localize("cliente.nroObraSocial", true), $this->encodeCharactersPDF( $nroObraSocial ));
		$this->renderLabelValue($this->localize("cliente.tipoAfiliado", true), $this->encodeCharactersPDF( $tipoAfiliado ));
		$this->Ln(5);

		$this->renderLabelValue($this->localize("cliente.telefonos", true), $this->encodeCharactersPDF( $telefonos ));
		$this->renderLabelValue($this->localize("cliente.domicilio", true), $this->encodeCharactersPDF( $domicilio ));
		$this->renderLabelValue($this->localize("cliente.localidad", true), $this->encodeCharactersPDF( $cliente->getLocalidad() ));
		$this->Ln(5);
		
		
	}
	
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
		$cliente = $this->getComponent()->getCliente();
		$titulo = RastyUtils::formatMessage($this->localize("historia.pdf.titulo"), array( $cliente->getNroHistoriaClinica(), $cliente->getNombre() ));
		$this->Cell( 30 , 5 , $this->encodeCharactersPDF( $titulo ), 0 , 0 , "L" );
				
		/* fecha */
//		$this->y = $this->tMargin;
//		$this->x= $maxWidth - 30 + $this->lMargin;
//		$this->initFontValue();
//		$this->Cell( 30 , 5 , TurnosUtils::formatDateToView( new \DateTime()) , 0 , 0 , "R" );
//		$this->Ln(5);
		
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