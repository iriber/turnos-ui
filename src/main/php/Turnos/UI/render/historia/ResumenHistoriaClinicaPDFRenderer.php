<?php

namespace Turnos\UI\render\historia;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\render\TurnosPDFRenderer;
use Turnos\Core\model\TipoDocumento;

use Rasty\conf\RastyConfig;
use Rasty\utils\RastyUtils;

use Rasty\components\AbstractComponent;

class ResumenHistoriaClinicaPDFRenderer extends HistoriaClinicaPDFRenderer{

	
	
	protected function renderPracticas(){
		
		$resumenes = $this->getComponent()->getResumenes();
		
		$this->initFontTitle();
		$this->Cell( 30 , 5 , $this->localize("historia.pdf.resumen.subtitulo") , 0 , 0 , "L" );
		$this->Ln(5);
		$this->Ln(5);
		
		$maxWidth = $this->getMaxWidth();

		$this->initFontValue();

		
		
		foreach ($resumenes as $resumen) {
			
			//($w, $h, $txt, $border=0, $align='J', $fill=false
			$this->MultiCell( $maxWidth , 5 , $this->encodeCharactersPDF( $resumen->getTexto() ) , 1 ,  "J", 1 );
			
			$this->Ln(5);
			
			
		}
		
		
	}

}
?>