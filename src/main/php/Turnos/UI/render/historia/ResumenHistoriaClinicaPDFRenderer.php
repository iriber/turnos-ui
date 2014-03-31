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
		
		$resumenOid = RastyUtils::getParamGET( 'resumenOid' ) ;
		
		$this->initFontTitle();
		$this->Cell( 30 , 5 , $this->localize("historia.pdf.resumen.subtitulo", true) , 0 , 0 , "L" );
		$this->Ln(5);
		$this->Ln(5);
		
		$maxWidth = $this->getMaxWidth();

		$this->initFontValue();

		
		
		foreach ($resumenes as $resumen) {
			
			if( empty($resumenOid) ){
			
				$this->MultiCell( $maxWidth , 5 , $this->encodeCharactersPDF( $resumen->getTexto() ) , 1 ,  "J", 1 );
			
				$this->Ln(5);
				
			}elseif($resumenOid == $resumen->getOid() ){
				
				$this->MultiCell( $maxWidth , 5 , $this->encodeCharactersPDF( $resumen->getTexto() ) , 1 ,  "J", 1 );
			
				$this->Ln(5);
			}
			
		}
		
		
	}

}
?>