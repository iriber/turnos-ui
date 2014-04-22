<?php

namespace Turnos\UI\components\stats;

use Turnos\UI\service\UIStatsService;

use Turnos\UI\utils\TurnosUtils;

use Turnos\UI\service\UIServiceFactory;

use Rasty\components\RastyComponent;
use Rasty\utils\RastyUtils;

use Rasty\utils\XTemplate;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Turno;

use Rasty\utils\LinkBuilder;

/**
 * Stats de Pacientes del mes.
 * 
 * @author bernardo
 * @since 09/04/2014
 */
class PacientesMes extends RastyComponent{
		
	private $anio;

	
	public function getType(){
		
		return "PacientesMes";
		
	}

	public function __construct(){
		
		$this->setAnio( date("Y") );
		
	}

	protected function parseLabels(XTemplate $xtpl){
		
		$xtpl->assign("lbl_totalPacientes",  $this->localize( "stats.pacientesMes.totalPacientes" ) );
		$xtpl->assign("lbl_importe",  $this->localize( "stats.pacientesMes.totalImporte" ) );
		
	}


	protected function parseXTemplate(XTemplate $xtpl){
		
		/*labels de la agenda*/
		$this->parseLabels($xtpl);
		
		//TODO buscamos las stats
		
		$importe = 0;
		$totalPacientes = 0;

		//obtenemos la cantidad de pacientes por mes.
		$clientesPorMes = UIStatsService::getInstance()->getClientesPorMes( $this->getAnio() );
		
		$cantidades = array();
 		$meses =  array();
		$importes = array();
		
 		foreach ($clientesPorMes as $mes => $values) {
 			$cantidades[] = $values["cantidad"];
 			$importes[] = $values["importe"];
 			$meses[] =TurnosUtils::formatMesToView($mes) ;
 			
 			$importe +=  $values["importe"];
 			$totalPacientes += $values["cantidad"];
 		}
		
		$xtpl->assign("totalPacientes", $totalPacientes );
		$xtpl->assign("importe",  TurnosUtils::formatMontoToView($importe) );
		
		$chart = $this->createChart( $meses, $cantidades );
		$this->parseChart($chart, $xtpl);
	}
	
	protected function createChart($meses, $cantidades){

		
		
 		$DataSet = new \pData; 
		//cantidad de pacientes por mes.
		$DataSet->AddPoint( $cantidades,"Serie1");
		//importes
		//$DataSet->AddPoint( $importes,"Serie2");
		//meses
		$DataSet->AddPoint( $meses,"Serie3");  
		
		$DataSet->AddSerie("Serie1");  
		//$DataSet->AddSerie("Serie2");  
		$DataSet->SetAbsciseLabelSerie("Serie3");  
		 
		$DataSet->SetSerieName("Pacientes","Serie1");  
		//$DataSet->SetSerieName("Importes","Serie2");  
		    
		 // Initialise the graph  
		 $chart = new \pChart(900,280);
		  
		 // Prepare the graph area  
		 $chart->setFontProperties( TurnosUtils::getChartsFontPath() . "tahoma.ttf",8);  
		 $chart->setGraphArea(60,40,850,250);  
		  
		 // Initialise graph area  
		 $chart->setFontProperties( TurnosUtils::getChartsFontPath() . "tahoma.ttf",8);
		  
		 // Draw minutes  
		 $DataSet->SetYAxisName("# Pacientes"); 
		 
		 $chart->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,0); //213,217,221- 150,90,110  
		 //$Test->drawGraphAreaGradient(40,40,40,-50);  
		 //$Test->drawGrid(4,TRUE,230,230,230,10);  
		 $chart->drawGrid(4,TRUE,150,150,150,10);
		 //$Test->setShadowProperties(3,3,0,0,0,30,4);  
		 $chart->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());  
		 $chart->clearShadow();  
		 $chart->drawFilledCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription(),.1,30);  
		 $chart->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);  
		 
		 
		 // Clear the scale  
		 $chart->clearScale();  
		  
		 // Write the legend (box less)  
		 $chart->setFontProperties(TurnosUtils::getChartsFontPath() . "tahoma.ttf",8);  
		 //$Test->drawLegend(530,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,150,150,150,FALSE);  
		 // Finish the graph  
 		$chart->drawLegend(530,5,$DataSet->GetDataDescription(),255,255,255,0,0,0,150,150,150);   
		// Write the title  
		$chart->setFontProperties(TurnosUtils::getChartsFontPath() . "MankSans.ttf",18);  
		//$Test->setShadowProperties(1,1,0,0,0);  
		//$Test->drawTitle(0,0,"ACD & ASR stats",255,255,255,660,30,TRUE);  
		//$chart->drawTitle(0,0,"Pacientes por mes",0,0,0,660,30,TRUE);
		$chart->clearShadow();  
 	
		
		return $chart;
	}
	
	protected function parseChart(\pChart $chart, XTemplate $xtpl ){
		
		$chartName = "pacientesMes" . date("YmdHis"); 
		$chartUri = TurnosUtils::getChartsWebPath() . "$chartName.png";
		$chartFile = TurnosUtils::getChartsAppPath() . "$chartName.png";
				 
		//elimino si ya existe
		if( file_exists($chartFile) )
		 	unlink( $chartFile );
				 
		//renderizo el chart en un archivo.
		$chart->Render( $chartFile );
		
		$xtpl->assign("chart_uri", $chartUri);
		$xtpl->parse("main.chart");
	}
	
	public function getAnio()
	{
	    return $this->anio;
	}

	public function setAnio($anio)
	{
	    $this->anio = $anio;
	}

	public function getAnios()
	{
		$anios = array();
		
		$anioActual = date("Y");
		
		for ($i = $anioActual; $i > 2010; $i--) {
			$anios[$i] = $i;
		}
		
	    return $anios;
	}
}
?>