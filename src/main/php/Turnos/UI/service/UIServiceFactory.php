<?php
namespace Turnos\UI\service;


/**
 * Factory de servicios de UI
 *  
 * @author bernardo
 *
 */
class UIServiceFactory {

	/**
	 * @return UIAusenciaService
	 */
	public static function getUIAusenciaService(){
	
		return UIAusenciaService::getInstance();	
	}
	
	/**
	 * @return UIClienteService
	 */
	public static function getUIClienteService(){
	
		return UIClienteService::getInstance();	
	}
	
	/**
	 * @return UIHorarioService
	 */
	public static function getUIHorarioService(){
	
		return UIHorarioService::getInstance();	
	}
	
	/**
	 * @return UILocalidadService
	 */
	public static function getUILocalidadService(){
	
		return UILocalidadService::getInstance();	
	}
	
	/**
	 * @return UINomencladorService
	 */
	public static function getUINomencladorService(){
	
		return UINomencladorService::getInstance();	
	}

	/**
	 * @return UIObraSocialService
	 */
	public static function getUIObraSocialService(){
	
		return UIObraSocialService::getInstance();	
	}
	
	/**
	 * @return UITurnoService
	 */
	public static function getUITurnoService(){
	
		return UITurnoService::getInstance();	
	}	
	
	/**
	 * @return UIProfesionalService
	 */
	public static function getUIProfesionalService(){
	
		return UIProfesionalService::getInstance();	
	}
	
	/**
	 * @return UIPracticaService
	 */
	public static function getUIPracticaService(){
	
		return UIPracticaService::getInstance();	
	}
	
	/**
	 * @return UIEspecialidadService
	 */
	public static function getUIEspecialidadService(){
	
		return UIEspecialidadService::getInstance();	
	}

	/**
	 * @return UIResumenHistoriaClinicaService 
	 */
	public static function getUIResumenHistoriaClinicaService(){
	
		return UIResumenHistoriaClinicaService::getInstance();	
	}
	
	/**
	 * @return UIStatsService
	 */
	public static function getUIStatsService(){
	
		return UIStatsService::getInstance();	
	}	
}