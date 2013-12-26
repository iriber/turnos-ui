<?php
namespace Turnos\UI\utils;

use Turnos\UI\components\agenda\AgendaTurnos;

use Rasty\utils\RastyUtils;

use Turnos\Core\model\Profesional;
use Turnos\Core\model\EstadoTurno;
use Turnos\Core\model\Ausencia;
use Rasty\i18n\Locale;
use Rasty\conf\RastyConfig;

use Cose\Security\model\Usergroup;
use Cose\Security\model\User;

/**
 * Utilidades para el sistema.
 *
 * @author bernardo
 * @since 03-08-2013
 */
class TurnosUtils {

	const TRN_USERGROUP_PROFESIONAL = 3;
	
	const TRN_DATE_FORMAT = 'd/m/Y';
	const TRN_DATETIME_FORMAT = 'd/m/y H:i:s';
	const TRN_TIME_FORMAT = 'H:i';
	
	const TRN_PRACTICA_DEFAULT = "42.01.01";
	
	//números
	const TRN_DECIMALES = '2';
	const TRN_SEPARADOR_DECIMAL = ',';
	const TRN_SEPARADOR_MILES = '.';

	//moneda.
	const TRN_MONEDA_SIMBOLO = '$';
	const TRN_MONEDA_ISO = 'ARS';
	const TRN_MONEDA_NOMBRE = 'Pesos Argentinos';
	const TRN_MONEDA_POSICION_IZQ = 1;

	
	public static function getWebPath(){
	
		return RastyConfig::getInstance()->getWebPath();
		
	}
	/**
	 * registramos la sesión del profesional
	 * @param Profesional $profesional
	 */
	public static function login(Profesional $profesional) {
		
		$appName = RastyConfig::getInstance()->getAppName();
		
        $_SESSION [$appName]["profesional_oid"] = $profesional->getOid();
		$_SESSION [$appName]["profesional_nombre"] = $profesional->getNombre();
        //$_SESSION [APP_NAME]["profesional_matricula"] = $profesional->getMatricula();
        
		self::setProfesionalAgenda($profesional);
    }

    /**
     * finalizamos la sesión del profesional
     */
    public static function logout() {
    	
    	$appName = RastyConfig::getInstance()->getAppName();
		
        $_SESSION [$appName]["profesional_oid"] = null;
        unset($_SESSION [$appName]["profesional_oid"]);
        unset($_SESSION [$appName]["profesional_nombre"]);
        unset($_SESSION [$appName]["profesional_matricula"]);
    }
	
    /**
     * @return true si hay un profesional logueado.
     */
    public static function isProfesionalLogged() {
    	
    	$appName = RastyConfig::getInstance()->getAppName();
    	
    	$data = RastyUtils::getParamSESSION($appName);
		
		$logueado =  ($data != "");
		
		if( $logueado ){
			$logueado = isset($data["profesional_oid"]) && !empty($data["profesional_oid"]); 
		}
		
		return $logueado;
    }
    
    /**
     * @return profesional logueado
     */
    public static function getProfesionalLogged() {
        
    	$appName = RastyConfig::getInstance()->getAppName();
    	
    	$data = RastyUtils::getParamSESSION( $appName );
    	
    	$profesional = new Profesional();
        $profesional->setOid($data["profesional_oid"]);
        $profesional->setNombre($data["profesional_nombre"]);
        
        return $profesional;
    }

    
    /**
     * seteamos el profesional de la agenda
     * @param Profesional $profesional
     */
	public static function setProfesionalAgenda(Profesional $profesional) {
		
		$appName = RastyConfig::getInstance()->getAppName();
    	
        $_SESSION [$appName]["agenda_profesional_oid"] = $profesional->getOid();
		$_SESSION [$appName]["agenda_profesional_nombre"] = $profesional->getNombre();
    }
    
    /**
     * @return profesional de la agenda
     */
    public static function getProfesionalAgenda() {
        
    	$appName = RastyConfig::getInstance()->getAppName();
    	
    	$data = RastyUtils::getParamSESSION( $appName );
    	
    	$profesional = new Profesional();
        $profesional->setOid($data["agenda_profesional_oid"]);
        $profesional->setNombre($data["agenda_profesional_nombre"]);
        
        return $profesional;
    }

	public static function isProfesionalAgenda() {
        
		$appName = RastyConfig::getInstance()->getAppName();
    	
    	$data = RastyUtils::getParamSESSION( $appName );
		
		$profesional =  ($data != "");
		
		if( $profesional ){
			$profesional = isset($data["agenda_profesional_oid"]) && !empty($data["agenda_profesional_oid"]); 
		}
		
		return $profesional;
		
    }
	
	/**
     * seteamos la fecha de la agenda
     * @param $fecha
     */
	public static function setFechaAgenda($fecha) {
		
		$appName = RastyConfig::getInstance()->getAppName();
    	
        $_SESSION [ $appName ]["agenda_fecha"] = $fecha;
    }
    
    /**
     * seteamos el tipo de agenda a visualizar
     * @param unknown_type $tipo
     */
    public static function setTipoAgenda($tipo) {
		
		$appName = RastyConfig::getInstance()->getAppName();
    	
        $_SESSION [ $appName ]["agenda_tipo"] = $tipo;
    }
    
    public static function isFechaAgenda() {
        
    	$appName = RastyConfig::getInstance()->getAppName();
    	
    	$data = RastyUtils::getParamSESSION($appName);
		
		$fecha =  ($data != "");
		
		if( $fecha ){
			$fecha = isset($data["agenda_fecha"]) && !empty($data["agenda_fecha"]); 
		}
		
		return $fecha;
		
    }
	
    /**
     * obtenemos el tipo de agenda a visualizar
     * @param unknown_type $tipo
     */
    public static function getTipoAgenda() {
		
		$appName = RastyConfig::getInstance()->getAppName();
    	
    	$data = RastyUtils::getParamSESSION( $appName );
    	
    	if (isset($data["agenda_tipo"]))
    		return $data["agenda_tipo"];
    	else return AgendaTurnos::AGENDA_DIARIA;	
    }
    
    /**
     * @return fecha de la agenda
     */
    public static function getFechaAgenda() {
        
    	$appName = RastyConfig::getInstance()->getAppName();
    	
    	$data = RastyUtils::getParamSESSION( $appName );
    	
    	if (isset($data["agenda_fecha"]))
    		return $data["agenda_fecha"];
    	else return new \DateTime();	
    }
    
	/**
	 * se formateo un monto a visualizar
	 * @param $monto
	 * @return string
	 */
	public static function formatMontoToView( $monto ){
	
		if( empty($monto) )
		$monto = 0;

		$res = $monto;
		//si es negativo, le quito el signo para agregarlo antes de la moneda.
		if( $monto < 0 ){
			$res = $res * (-1);
		}
			
		$res = number_format ( $res ,  self::TRN_DECIMALES , self::TRN_SEPARADOR_DECIMAL, self::TRN_SEPARADOR_MILES );



		if( self::TRN_MONEDA_POSICION_IZQ ){
			$res = self::TRN_MONEDA_SIMBOLO . $res;
				
		}else
		$res = $res. self::TRN_MONEDA_SIMBOLO ;

		//si es negativo lo mostramos rojo y le agrego el signo.
		if( $monto < 0 ){
			$res = "<span style='color:red;'>- $res</span>";
		}

		return $res;
	}


	//Formato fecha yyyy-mm-dd
	public static function differenceBetweenDates($fecha_fin, $fecha_Ini, $formato_salida = "d") {
		$valueFI = str_replace('/', '-', $fecha_Ini);
		$valueFF = str_replace('/', '-', $fecha_fin);
		$f0 = strtotime($valueFF);
		$f1 = strtotime($valueFI);
		if ($f0 < $f1) {
			$tmp = $f1;
			$f1 = $f0;
			$f0 = $tmp;
		}
		return date($formato_salida, $f0 - $f1);
	}


	public static function getFilterOptionItems($oManager, $valueProperty, $labelProperty) {

		/*
		$oCriteria = new CdtSearchCriteria();
		$oCriteria->addOrder($labelProperty, "ASC");
		$entities = $oManager->getEntities($oCriteria);
		
		
		
		foreach ($entities as $oEntity) {
			$value = CdtReflectionUtils::doGetter($oEntity, $valueProperty);
			$label = CdtReflectionUtils::doGetter($oEntity, $labelProperty);
			$items[$value] = $label;
		}*/
		$items = array();
		return $items;
	}

	

	public static function formatDateToView($value, $format=self::TRN_DATE_FORMAT) {
		
		$res = "";
		if( !empty( $value) )
			$res = $value->format($format);
		else $res = "";
		
		$meses = array (
			"January" => "Enero",
			"Febraury" => "Febrero",
			"March" => "Marzo",
			"April" => "Abril",
			"May" => "Mayo",
			"June" => "Junio",
			"July" => "Julio",
			"August" => "Agosto",
			"September" => "Septiembre",
			"October" => "Octubre",
			"November" => "Noviembre",
			"December" => "Diciembre",
			"Jan" => "Ene",
			"Feb" => "Feb",
			"Mar" => "Mar",
			"Apr" => "Abr",
			"May" => "May",
			"Jun" => "Jun",
			"Jul" => "Jul",
			"Aug" => "Ago",
			"Sep" => "Sep",
			"Oct" => "Oct",
			"Nov" => "Nov",
			"Dec" => "Dic"
		);
		
		$dias = array(
			'Monday' => 'Lunes',
			'Tuesday' => 'Martes',
			'Wednesday' => 'Miércoles',
			'Thursday' => 'Jueves',
			'Friday' => 'Viernes',
			'Saturday' => 'Sábado',
			'Sunday' => 'Domingo',
			'Mon' => 'Lun',
			'Tue' => 'Mar',
			'Wed' => 'Mie',
			'Thu' => 'Jue',
			'Fri' => 'Vie',
			'Sat' => 'Sab',
			'Sun' => 'Dom',
		);
		foreach ($meses as $key => $value) {
			$res = str_replace($key, $value, $res);
		}
		foreach ($dias as $key => $value) {
			$res = str_replace($key, $value, $res);
		}
		
		return $res ;
		/*
		$value = str_replace('/', '-', $value);
		
		if (!empty($value)) {
			$dt = date($format, strtotime($value));
		}else
		$dt = "";

		return $dt;
		*/
	}

	public static function formatDateToPersist($value) {

		return $value->format("Y-m-d");
		
		/*		
		$value = str_replace('/', '-', $value);
		
		if (!empty($value))
		$dt = date("Y-m-d", strtotime($value));
		else
		$dt = "";
		return $dt;*/
	}

	public static function formatDateTimeToView($value) {
		
		if(!empty($value))
			return $value->format(self::TRN_DATETIME_FORMAT);
		else
			return "";
		/*
		$value = str_replace('/', '-', $value);
		
		if (!empty($value)) {
			$dt = date(self:TRN_DATE_FORMAT, strtotime($value));
		}else
		$dt = "";

		return $dt;*/
	}

	public static function formatDateTimeToPersist($value) {
		
		return $value->format("Y-m-d H:i:s");
		
		/*
		$value = str_replace('/', '-', $value);
		
		if (!empty($value))
		$dt = date("Y-m-d H:i:s", strtotime($value));
		else
		$dt = "";

		return $dt;*/
	}

	public static function addDays($date, $dateFormat="", $days){

		$date->modify("+$days day");
		return $date;
		/*
		$newDate = strtotime ( "+$days day" , strtotime ( $date ) ) ;
		$newDate = date ( $dateFormat , $newDate );
		
		return $newDate;
		*/
	}

	public static function substractDays($date, $dateFormat, $days){

		$date->modify("-$days day");
		return $date;
		/*
		$newDate = strtotime ( "-$days day" , strtotime ( $date ) ) ;
		$newDate = date ( $dateFormat , $newDate );

		return $newDate;
		*/
	}

	public static function addMinutes($date, $dateFormat, $minutes){
		
		//$date->modify("+$minutes minutes");
		//return $date;
		
		$newDate = strtotime ( "+$minutes minutes" , strtotime ( $date ) ) ;
		$newDate = date ( $dateFormat , $newDate );
		
		return $newDate;
	}
	
	public static function isSameDay( $dt_date, $dt_another){

		return $dt_date->format("Ymd") == $dt_another->format("Ymd");
		 
		/*
		$dt_dateAux = strtotime ( $dt_date ) ;
		$dt_dateAux = date ( "Ymd" , $dt_dateAux );

		$dt_anotherAux = strtotime ( $dt_another ) ;
		$dt_anotherAux = date ( "Ymd" , $dt_anotherAux );

		return $dt_dateAux == $dt_anotherAux ;*/
	}


	public static function formatTimeToView($value, $format=self::TRN_TIME_FORMAT) {
		
		if(!empty($value))
		
			return $value->format($format);

		else return "";	
		/*
		$value = str_replace('/', '-', $value);
		
		if (!empty($value)) {
			$dt = date(self:TRN_TIME_FORMAT, strtotime($value));
		}else
		$dt = "";

		return $dt;*/
	}

	public static function getHorasItems() {
		
		$desde = new \DateTime();
		$desde->setTime(0,0,0);
		$duracion = 15;
		$i=0;
		while( $i<97 ){
			
			$items[$desde->format("H:i")] = $desde->format("H:i");
			
			$desde->modify("+$duracion minutes");
			
			$i++;	
			
		}
		
		return $items;
		
	}

	public static function formatEdad( $edad ){
	
		if( !empty($edad) ){
		
			if( $edad > 1)
				return "$edad años";
			else
				return "$edad año";
		}return "";
	}
	
	public static function getEdad( $fecha ){

		$fechaNac = $fecha;
		
		if ($fechaNac != null ){
			
			$hoy = new \DateTime();
			
			$dia = $hoy->format("d");
			$mes = $hoy->format("m");
			$anio = $hoy->format("Y");
			 
			//fecha de nacimiento
			$dia_nac = $fechaNac->format("d");
			$mes_nac = $fechaNac->format("m");
			$anio_nac = $fechaNac->format("Y");
			
			//si el mes es el mismo pero el día inferior aun no ha cumplido años, le quitaremos un año al actual
			 
			if (($mes_nac == $mes) && ($dia_nac > $dia)) {
				$anio=($anio-1); 
			}
			 
			//si el mes es superior al actual tampoco habrá cumplido años, por eso le quitamos un año al actual
			 
			if ($mes_nac > $mes) {
				$anio=($anio-1);
			}
			 
			//ya no habría mas condiciones, ahora simplemente restamos los años y mostramos el resultado como su edad
			 
			$edad=($anio-$anio_nac);    	    
				
		}
		else
			$edad = "";
		
    	return $edad;
	}
	
	

	
	public static function dayOfDate(\DateTime $value) {
		
		return $value->format("d");
		
		/*
		$value = str_replace('/', '-', $value);
		
		if (!empty($value)) {
			$dt = date("d", strtotime($value));
		}else
		$dt = "";

		return $dt;*/
	}

	public static function monthOfDate($value) {
		
		return $value->format("m");
		
		/*
		$value = str_replace('/', '-', $value);
		
		if (!empty($value)) {
			$dt = date("m", strtotime($value));
		}else
		$dt = "";

		return $dt;*/
	}
	
	public static function yearOfDate($value) {
		
		return $value->format("Y");
		
		/*
		$value = str_replace('/', '-', $value);
		
		if (!empty($value)) {
			$dt = date("Y", strtotime($value));
		}else
		$dt = "";

		return $dt;*/
	}
	
	public static function strtotime($value) {
		
		$value = str_replace('/', '-', $value);
		
		return strtotime($value);
	}


	public static function newDateTime($value) {
		
		$value = str_replace('/', '-', $value);
		$time = strtotime($value);
		
		$dateTime = new \DateTime();
		$dateTime->setDate(date("Y", $time), date("m", $time), date("d", $time));
		
		return $dateTime;
	}
	
	public static function getEstadoTurnoCss($estadoTurno){
		$estilos = array(
						EstadoTurno::EnSala => "turno_ensala",
						EstadoTurno::Asignado=> "turno_asignado",
						EstadoTurno::Atendido=> "turno_atendido",
						EstadoTurno::EnCurso=> "turno_encurso"
						);
		return $estilos[$estadoTurno];
	}
	
	public static function localize($keyMessage){
	
		return Locale::localize( $keyMessage );
	}
	
	
	public static function localizeEntities($enumeratedEntities){
		
		$items = array();
		
		foreach ($enumeratedEntities as $key => $keyMessage) {
			$items[$key] = self::localize($keyMessage);
		}
		
		return $items;
	}
	
	public static function formatMessage($msg, $params){
		
		if(!empty($params)){
			
			$count = count ( $params );
			$i=1;
			while ( $i <= $count ) {
				$param = $params [$i-1];
				
				$msg = str_replace('$'.$i, $param, $msg);
				
				$i ++;
			}
			
		}
		return $msg;
		
	
	}
	
	
	public static function getMensajeAusencia(Ausencia $ausencia){

		$formatoFecha = "l j F-Y";
		$formatoHora = "G:i";

		$observacionesDefault = Locale::localize("ausencia.observaciones.default.msg");
		$mensajePorFecha = Locale::localize("ausencia.porFecha.msg");
		$mensajePorFechaHorario = Locale::localize("ausencia.porFechaHorario.msg");
		$mensajePorRangoFecha = Locale::localize("ausencia.porRangoFecha.msg");
		$mensajePorRangoFechaHorario = Locale::localize("ausencia.porRangoFechaHorario.msg");
		
		$fechaDesde = self::formatDateToView( $ausencia->getFechaDesde(), $formatoFecha );
		$fechaHasta = self::formatDateToView( $ausencia->getFechaHasta(), $formatoFecha ) ;
		$horaDesde = self::formatTimeToView( $ausencia->getHoraDesde(), $formatoHora );
		$horaHasta = self::formatTimeToView( $ausencia->getHoraHasta(), $formatoHora );

		$observaciones = $ausencia->getObservaciones();
		if(empty($observaciones))
			$observaciones = $observacionesDefault;
		
		$mensaje = "";
		$params = array();
		
		if( $ausencia->isPorFecha() ){
			
			$params[] = $observaciones;
			$params[] = $fechaDesde;
			$mensaje = $mensajePorFecha;
			
		}elseif( $ausencia->isPorFechaHorario()){
			
			$params[] = $observaciones;
			$params[] = $fechaDesde;
			$params[] = $horaDesde;
			$params[] = $horaHasta;
			$mensaje = $mensajePorFechaHorario;
			
		}elseif( $ausencia->isPorRangoFecha()){
			
			$params[] = $observaciones;
			$params[] = $fechaDesde;
			$params[] = $fechaHasta;
			$mensaje = $mensajePorRangoFecha;
			
		}elseif( $ausencia->isPorRangoFechaHorario()){
			
			$params[] = $observaciones;
			$params[] = $fechaDesde;
			$params[] = $fechaHasta;
			$params[] = $horaDesde;
			$params[] = $horaHasta;
			$mensaje = $mensajePorRangoFechaHorario;
			
		}
		
		$mensaje = self::formatMessage($mensaje, $params );
		return $mensaje;
	}
	
	public static function isProfesional( User $user ){
	
		$usergroup = new Usergroup();
		$usergroup->setOid(TurnosUtils::TRN_USERGROUP_PROFESIONAL);
		return $user->hasUsergroup( $usergroup );
			
	}
	
	public static function getNewDate($dia,$mes,$anio){
	
		$date = new \DateTime();
		$date->setDate($anio, $mes, $dia);
		return $date;
	}
	
	
	public static function getFirstDayOfWeek(\Datetime $fecha){
	
		$f = new \Datetime();
		$f->setTimestamp( $fecha->getTimestamp() );
    	
		//si es lunes, no hacemos nada, sino, buscamos el lunes anterior.
		if( $f->format("N") > 1 )
			$f->modify("last monday");
    	
    	return $f;
	}
	
	
	public static function getLastDayOfWeek(\Datetime $fecha){
	
		$f = new \Datetime();
		$f->setTimestamp( $fecha->getTimestamp() );
    	$f->modify("next monday");
    	
    	//si no es lunes, restamos un día.
    	if( $fecha->format("N") > 1 )
			$f->sub(new \DateInterval('P1D'));
    	
    	return $f;
	}
	
	public static function fechasIguales(\Datetime $fecha1, \Datetime $fecha2){
		return $fecha1->format("Ymd") == $fecha2->format("Ymd");
	}
	
}