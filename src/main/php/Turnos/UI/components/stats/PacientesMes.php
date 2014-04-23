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
		$xtpl->assign("lbl_anio",  $this->localize( "stats.pacientesMes.anio" ) );
		
	}


	protected function parseXTemplate(XTemplate $xtpl){
		
		/*labels de la agenda*/
		$this->parseLabels($xtpl);
		
		//TODO buscamos las stats
		
		$importe = 0;
		$totalPacientes = 0;

		//obtenemos la cantidad de pacientes por mes.
		$anio = $this->getAnio();
		if(empty($anio)){
			$anio = 2014;
		}
		$clientesPorMes = UIStatsService::getInstance()->getClientesPorMes( $anio );
		
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
		//$DataSet->setColorPalette(2,50,50,50); 
		 // Initialise the graph  
		 $chart = new \pChart(900,280);
		  
		 $serieSettings = array("R"=>229,"G"=>11,"B"=>11,"Alpha"=>80);
		 

		 // Prepare the graph area  
		 $chart->setFontProperties( TurnosUtils::getChartsFontPath() . "tahoma.ttf",8);
		 
		 
		 $chart->setGraphArea(60,40,850,250);  
		 
		  
		 // Initialise graph area  
		 $chart->setFontProperties( TurnosUtils::getChartsFontPath() . "tahoma.ttf",8);
		  
		 // Draw minutes  
		 $DataSet->SetYAxisName("# Pacientes"); 
		 
		 $chart->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,50,50,50,TRUE,0,0); //213,217,221- 150,90,110  
		 //$chart->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,90,110,TRUE,0,0); //213,217,221- 150,90,110
		 //$chart->drawGraphAreaGradient(40,40,40,-50);  
		 //$chart->drawGrid(4,TRUE,230,230,230,10);  
		 $chart->drawGrid(4,TRUE,50,50,50,10);
		 
		 $chart->setFontProperties(TurnosUtils::getChartsFontPath() . "tahoma.ttf",8);
 		 $chart->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie1");
  
		 //$Test->setShadowProperties(3,3,0,0,0,30,4);  
		 $chart->setColorPalette(2,50,50,50);   
		 $chart->drawCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription());  
		 //$chart->clearShadow();  
		 $chart->drawFilledCubicCurve($DataSet->GetData(),$DataSet->GetDataDescription(),.1,40);  
		 //$chart->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);  
		 
		 // Clear the scale  
		 $chart->clearScale();  
		  
		 // Write the legend (box less)  
		 $chart->setFontProperties(TurnosUtils::getChartsFontPath() . "tahoma.ttf",10);  
		 //$Test->drawLegend(530,5,$DataSet->GetDataDescription(),0,0,0,0,0,0,150,150,150,FALSE);  
		 // Finish the graph  
 		$chart->drawLegend(530,5,$DataSet->GetDataDescription(),255,255,255,0,0,0,50,50,50);
 		   
		// Write the title  
		$chart->setFontProperties(TurnosUtils::getChartsFontPath() . "MankSans.ttf",18);  
		//$Test->setShadowProperties(1,1,0,0,0);  
		//$Test->drawTitle(0,0,"ACD & ASR stats",255,255,255,660,30,TRUE);  
		//$chart->drawTitle(0,0,"Pacientes por mes",0,0,0,660,30,TRUE);
		$chart->clearShadow();  
 	
		
		return $chart;
		
		/*
		// Dataset definition      
 $DataSet = new \pData;  
 $DataSet->AddPoint(array(7.1,9.1,10,9.7,8.2,5.7,2.6,-0.9,-4.2,-7.1,-9.1,-10,-9.7,-8.2,-5.8),"Serie3");  
 $DataSet->AddPoint(array("Jan","Jan","Jan","Feb","Feb","Feb","Mar","Mar","Mar","Apr","Apr","Apr","May","May","May"),"Serie4");  
 $DataSet->AddAllSeries();  
 $DataSet->SetAbsciseLabelSerie("Serie4");  
 $DataSet->SetSerieName("Temperature","Serie3");  
 $DataSet->SetYAxisName("Temperature");  
 $DataSet->SetXAxisName("Month of the year");  
   
 // Initialise the graph     
 $Test = new \pChart(700,230);  
 $Test->reportWarnings("GD");  
 $Test->setFixedScale(-12,12);  
 $Test->setFontProperties(TurnosUtils::getChartsFontPath() . "tahoma.ttf",8);     
 $Test->setGraphArea(65,30,570,185);     
 $Test->drawFilledRoundedRectangle(7,7,693,223,5,240,240,240);     
 $Test->drawRoundedRectangle(5,5,695,225,5,230,230,230);     
 $Test->drawGraphArea(255,255,255,TRUE);  
 $Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,150,150,150,TRUE,0,2,TRUE,3);     
 $Test->drawGrid(4,TRUE,230,230,230,50);  
  
 // Draw the 0 line     
 $Test->setFontProperties(TurnosUtils::getChartsFontPath() . "tahoma.ttf",6);     
 $Test->drawTreshold(0,143,55,72,TRUE,TRUE);     
   
 // Draw the area  
 $DataSet->RemoveSerie("Serie4");  
 $DataSet->RemoveSerie("Serie3");  
  
 // Draw the line graph  
 $Test->setLineStyle(1,6);  
 $DataSet->RemoveAllSeries();  
 $DataSet->AddSerie("Serie3");  
 $Test->drawLineGraph($DataSet->GetData(),$DataSet->GetDataDescription());     
 $Test->drawPlotGraph($DataSet->GetData(),$DataSet->GetDataDescription(),3,2,255,255,255);     
  
 // Write values on Serie1 & Serie2  
 $Test->setFontProperties(TurnosUtils::getChartsFontPath() . "tahoma.ttf",18);     
 $Test->writeValues($DataSet->GetData(),$DataSet->GetDataDescription(),"Serie3");     
   
 // Finish the graph     
 $Test->setFontProperties(TurnosUtils::getChartsFontPath() . "tahoma.ttf",8);     
 $Test->drawLegend(590,90,$DataSet->GetDataDescription(),255,255,255);     
 $Test->setFontProperties(TurnosUtils::getChartsFontPath() . "tahoma.ttf",10);     
 $Test->drawTitle(60,22,"example 15",50,50,50,585);  
  
 return $Test;*/
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
	
	
	protected function initObserverEventType(){
		$this->addEventType( "Anio" );
	}
	
}
?>