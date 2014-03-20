

/* PRACTICAS */
function agregarPractica(link, cliente, profesional){

	url = link + "?clienteOid="+cliente+ "?profesionalOid="+profesional;
	gotoLink( url );
	
}

function borrarPractica(link, oid, cliente_oid, cliente, onSuccess){
	$title = "Borrar práctica";
	$texto = "Confirma borrar la pr&aacute;ctica ?";
	$params = new Array();
	$params["url"] = link + "?oid="+oid;
	$params["onSuccess"] = onSuccess;

	jAlertConfirm( $title, $texto, doBorrarPractica, $params );
}

function doBorrarPractica( params ){
	
	$.ajax({
    		  	url: params["url"],
    		  	type: "GET",
    		  	dataType: "json",
    		  	cache: false,
    			complete:function(){
    				//$("#loading").hide();
    				//$("#current_action").html("");
    			},
    		  	success: function(data){
        		  	
    				//$("#nextOffer").html("");
    				if( data != null && data["error"]!=null){
    					msg = data["error"];
    					showErrorMessage(msg);
    				}
    				if( data != null && data["info"]!=null){
						//quitamos de la agenda la fila
						//que corresponde al turno eliminado
						//$("#turno_" + oid).fadeOut(500);
    				}
    				params["onSuccess"](data);
    		  	}
    	});      
}

function editarPractica(link, practicaOid){

	url = link + "?oid="+practicaOid;
	gotoLink( url );
	
}


/* TURNOS */

function agregarTurno(link, hora, fecha, profesional){

	url = link + "?hora="+hora;
	gotoLink( url );
	
}

function borrarTurno(link,oid, hora, persona, onSuccess){
	
	$title = "Borrar turno";
	$texto = "Confirma borrar el turno de las " + hora + " de " + persona;
	$params = new Array();
	$params["url"] = link + "?oid="+oid;
	$params["oid"] = oid;
	$params["onSuccess"] = onSuccess;
	
	jAlertConfirm( $title, $texto, doBorrarTurno, $params );
}

function doBorrarTurno( params ){
	
	$.ajax({
	  	url: params["url"],
	  	type: "GET",
	  	dataType: "json",
	  	cache: false,
		complete:function(){
			//$("#loading").hide();
			//$("#current_action").html("");
		},
	  	success: function(data){
		  	
			//$("#nextOffer").html("");
			if( data != null && data["error"]!=null){
				msg = data["error"];
				showErrorMessage(msg);
			}
			if( data != null && data["info"]!=null){
				//quitamos de la agenda la fila
				//que corresponde al turno eliminado
				//$("#turno_" + oid).fadeOut(500);
				
			}
			params["onSuccess"](data);
	  	}
	});
}



function editarTurno(link, turnoOid){

	url = link + "?oid="+turnoOid;
	gotoLink( url );
	
}

/**
 * se finaliza un turno
 * @param link
 * @param hora
 * @param cliente
 * @param clienteOid
 */
function finalizarTurno(link, oid, hora, cliente, clienteOid, onSuccess){
	
	$.ajax({
	  	url:  link + "?oid="+oid,
	  	type: "GET",
	  	cache: false,
		complete:function(){
			//$("#loading").hide();
			//$("#current_action").html("");
		},
	  	success: function(data){
		  	
			//$("#nextOffer").html("");
			data = $.parseJSON(data);
			if( data != null && data["error"]!=null){
				msg = data["error"];
				showErrorMessage(msg);
			}
			if( data != null && data["info"]!=null){
				//quitamos de la agenda la fila
				//que corresponde al turno eliminado
				//$("#turno_" + oid).fadeOut(500);
			}
			onSuccess(data);
	  	}
	});
}

/**
 * se inicia un turno
 * @param link
 * @param oid
 * @param hora
 * @param cliente
 * @param clienteOid
 */
function iniciarTurno(link, oid, hora, cliente, clienteOid, onSuccess){
	
	$.ajax({
	  	url:  link + "?oid="+oid,
	  	type: "GET",
	  	dataType: "json",
	  	cache: false,
		complete:function(){
		},
	  	success: function(data){
			if( data != null && data["error"]!=null){
				msg = data["error"];
				showErrorMessage(msg);
			}
			if( data != null && data["info"]!=null){
			}
			onSuccess(data);
	  	}
	});

}

/**
 * se pone enSala un turno
 * @param link
 * @param hora
 * @param cliente
 * @param clienteOid
 */
function turnoEnSala(link, oid, hora, cliente, clienteOid, onSuccess){
	$.ajax({
	  	url:  link + "?oid="+oid,
	  	type: "GET",
	  	dataType: "json",
	  	cache: false,
		complete:function(){
		},
	  	success: function(data){
			if( data != null && data["error"]!=null){
				msg = data["error"];
				showErrorMessage(msg);
			}
			if( data != null && data["info"]!=null){
			}
			onSuccess(data);
	  	}
	});
}

/**
 * se pone en estado Asignado un turno
 * @param link
 * @param oid 
 * @param hora
 * @param cliente
 * @param clienteOid
 */
function turnoAsignado(link, oid, hora, cliente, clienteOid, onSuccess){
	$.ajax({
	  	url:  link + "?oid="+oid,
	  	type: "GET",
	  	dataType: "json",
	  	cache: false,
		complete:function(){
		},
	  	success: function(data){
			if( data != null && data["error"]!=null){
				msg = data["error"];
				showErrorMessage(msg);
			}
			if( data != null && data["info"]!=null){
			}
			onSuccess(data);
	  	}
	});
}


/* ausencias */
function borrarAusencia(link,oid, profesionalOid, onSuccess){
	$title = "Borrar Notificación de ausencia";
	$texto = "Confirma borrar la Notificaci&oacute;n?";
	$params = new Array();
	$params["url"] = link + "?oid="+oid;
	$params["onSuccess"] = onSuccess;
	$params["profesionalOid"] = profesionalOid;
	jAlertConfirm( $title, $texto, doBorrarAusencia, $params );
}

function doBorrarAusencia( params ){
	$.ajax({
	  	url: params["url"],
	  	type: "GET",
	  	dataType: "json",
	  	cache: false,
		complete:function(){
		},
	  	success: function(data){
		  	
			if( data != null && data["error"]!=null){
				msg = data["error"];
				showErrorMessage(msg);
			}
			if( data != null && data["info"]!=null){
				
			}
			
			params["onSuccess"](data, params["profesionalOid"]);
	  	}
	});        
	
}

function doGetProfefionalesByEspecialidad(especialidadOid, onSuccess ){
	
	var link = "findProfesionalesByEspecialidad.json" + "?oid=" + especialidadOid;
	$.ajax({
    		  	url: link,
    		  	type: "GET",
    		  	dataType: "json",
    		  	cache: false,
    			complete:function(){
    				//$("#loading").hide();
    				//$("#current_action").html("");
    			},
    		  	success: function(data){
        		  	
    				//$("#nextOffer").html("");
    				if( data != null && data["error"]!=null){
    					msg = data["error"];
    					showErrorMessage(msg);
    				}
    				if( data != null && data["info"]!=null){
						//quitamos de la agenda la fila
						//que corresponde al turno eliminado
						//$("#turno_" + oid).fadeOut(500);
    				}
    				onSuccess(data["profesionales"]);
    				
    		  	}
    	});      
}

function updateClock( divId ){
	var d = new Date();
	var sec = d.getSeconds();
	var min = d.getMinutes();
	var hs = d.getHours();
	
	if(sec < 10) 
		sec = "0" + sec;
	
	if(min < 10) 
		min = "0" + min;
	
	if(hs < 10) 
		hs = "0" + hs;
	
	var time = hs + ":" + min + ":" + sec;
	$(divId).html( time );
}


function borrarHorario(link, oid, dia, desde, hasta, onSuccess){
	$title = "Borrar horario";
	$texto = "Confirma borrar el horario del dia " + dia + " de " + desde +  " a " + hasta +"?";
	$params = new Array();
	$params["url"] = link + "?oid="+oid;
	$params["onSuccess"] = onSuccess;

	jAlertConfirm( $title, $texto, doBorrarHorario, $params );
}

function doBorrarHorario( params ){
	
	$.ajax({
    		  	url: params["url"],
    		  	type: "GET",
    		  	dataType: "json",
    		  	cache: false,
    			complete:function(){
    				//$("#loading").hide();
    				//$("#current_action").html("");
    			},
    		  	success: function(data){
        		  	
    				//$("#nextOffer").html("");
    				if( data != null && data["error"]!=null){
    					msg = data["error"];
    					showErrorMessage(msg);
    				}
    				if( data != null && data["info"]!=null){
						//quitamos de la agenda la fila
						//que corresponde al turno eliminado
						//$("#turno_" + oid).fadeOut(500);
    				}
    				params["onSuccess"](data);
    		  	}
    	});      
}

/* RESUMEN HC */
function agregarResumenHistoriaClinica(link, cliente, profesional){

	url = link + "?clienteOid="+cliente+ "?profesionalOid="+profesional;
	gotoLink( url );
	
}

function borrarResumenHistoriaClinica(link, oid, cliente_oid, cliente, onSuccess){
	$title = "Borrar Resumen Historia Clínica";
	$texto = "Confirma borrar el resumen?";
	$params = new Array();
	$params["url"] = link + "?oid="+oid;
	$params["onSuccess"] = onSuccess;

	jAlertConfirm( $title, $texto, doBorrarResumenHistoriaClinica, $params );
}

function doBorrarResumenHistoriaClinica( params ){
	
	$.ajax({
    		  	url: params["url"],
    		  	type: "GET",
    		  	dataType: "json",
    		  	cache: false,
    			complete:function(){
    				//$("#loading").hide();
    				//$("#current_action").html("");
    			},
    		  	success: function(data){
        		  	
    				//$("#nextOffer").html("");
    				if( data != null && data["error"]!=null){
    					msg = data["error"];
    					showErrorMessage(msg);
    				}
    				if( data != null && data["info"]!=null){
						//quitamos de la agenda la fila
						//que corresponde al turno eliminado
						//$("#turno_" + oid).fadeOut(500);
    				}
    				params["onSuccess"](data);
    		  	}
    	});      
}

function editarResumenHistoriaClinica(link, resumenOid){

	url = link + "?oid="+resumenOid;
	gotoLink( url );
	
}
