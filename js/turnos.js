
function wait( id ){
	$( id ).fadeTo("fast", 0.33);
}

function wakeUp( id ){
	$( id ).fadeTo("fast", 1);
}


/* PRACTICAS */
function agregarPractica(link, cliente, profesional){

	url = link + "?clienteOid="+cliente+ "?profesionalOid="+profesional;
	gotoLink( url );
	
}

function borrarPractica(link, oid, cliente_oid, cliente, onSuccess){
	jConfirm("Confirma borrar la pr&aacute;ctica ?", 'Confirmar', function(r) {
        if (r) {
        	$.ajax({
    		  	url: link + "?oid="+oid,
    		  	type: "GET",
    		  	datatype: "json",
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
    				onSuccess(data);
    		  	}
    		});        
            return false;
        } else {
            return false;
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
	jConfirm("Confirma borrar el turno de las " + hora + " de " + persona, 'Confirmar', function(r) {
        if (r) {
        	$.ajax({
    		  	url: link+"?oid="+oid,
    		  	type: "GET",
    		  	datatype: "json",
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
    				onSuccess(data);
    		  	}
    		});        
            return false;
        } else {
            return false;
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
	/*
	jConfirm("Confirma finalizar la atención del turno de las " + hora + " de " + cliente , 'Confirmar', function(r) {

        if (r) {
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
            return false;
        } else {
            return false;
        }
    });
	*/
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
	/*
	jConfirm("Confirma dar curso al turno de las " + hora + " de " + cliente, 'Confirmar', function(r) {
		if (r) {
        	window.location.href= link + "?oid="+oid;
            return false;
        } else {
            return false;
        }       
    });
	*/
	//window.location.href= link + "?oid="+oid;
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
	/*
	jConfirm("Confirma que " + cliente + " está 'En Sala'", 'Confirmar', function(r) {
        if (r) {
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
            return false;
        } else {
            return false;
        }
    });
	*/
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
/* GRID */

function submitBuscar(webpath, filterId, resultId){

	var formData = $( filterId ).serialize();
	
	//$( resultId ).fadeTo("fast", 0.33);
	right = ($(window).width() / 2) - (32);		
	htmlSearching = "<div style='position:absolute; right:" + right + "px;top:40px;'><img src='" + webpath + "/css/images/loading.gif' /></div>";
	$( resultId ).html($( resultId ).html() + htmlSearching);
		
	$.ajax({
		  url: webpath + "EntityGrid.rasty",
		  type: "POST",
		  data: formData,
		  cache: false,
		  success: function(content){
		    
			$( resultId ).html(content);
			//$( resultId ).fadeTo("fast", 1);
			
		  }
		});	
}

function cleanForm(formId) {

	form = $('#'+formId);
	
    // iterate over all of the inputs for the form

    // element that was passed in

    $(':input', form).each(function() {

      var type = this.type;

      var tag = this.tagName.toLowerCase(); // normalize case

      // it's ok to reset the value attr of text inputs,

      // password inputs, and textareas

      if (type == 'text' || type == 'password' || tag == 'textarea')

        this.value = "";

      // checkboxes and radios need to have their checked state cleared

      // but should *not* have their 'value' changed

      else if (type == 'checkbox' || type == 'radio')

        this.checked = false;

      // select elements need to have their 'selectedIndex' property set to -1

      // (this works for both single and multiple select elements)

      else if (tag == 'select')

        this.selectedIndex = -1;

    });

 }

function submitFormulario( formId ){
	var resp =  validate( formId );
	
	if( resp ){
	
		$( "#" + formId ).submit();
		
	}
	
}


function submitFormularioAjax(webpath, url, formId, resultId){

	var formData = $( formId ).serialize();
	
	//$( resultId ).fadeTo("fast", 0.33);
	right = ($(window).width() / 2) - (32);		
	htmlSearching = "<div style='position:absolute; right:" + right + "px;top:40px;'><img src='" + webpath + "/css/images/loading.gif' /></div>";
	
	$( resultId ).html($( resultId ).html() + htmlSearching);
		
	$.ajax({
		  url: webpath + url,
		  type: "POST",
		  data: formData,
		  cache: false,
		  success: function(content){
		    
			$( resultId ).html(content);
			
		  }
		});	
}


function gotoLink( link ){
	
	window.location.href = link;
}

function gotoLinkPopup( link, resultId, title, height, width ){
	
	if( width == undefined )
		width = "80%";
	
	if( height == undefined )
		height = "600";
	
	var uiDialog = resultId;
	var dialogOpts = {
			title: title,	
            modal : false,
            autoOpen : false,
            //bgiframe : false,
            height : height,
            width : width,
            open : function() {
                $(uiDialog).load(link);
            }
        };
    $(uiDialog).children().remove();
    $(uiDialog).dialog("destroy");
    $(uiDialog).dialog(dialogOpts);
    $(uiDialog).dialog("open");
}

/* FINDER */
function closeFinderPopup(resultId){
	var uiDialog = resultId;
	$(uiDialog).children().remove();
    $(uiDialog).dialog("destroy");
    
}
function openFinderPopup(webpath, filterType, fCallback, resultId){
	var url = webpath + "FinderPopup.rasty?filterType=" + filterType + "&onSelectCallback=" + fCallback;
	var uiDialog = resultId;
	var dialogOpts = {
            //title : "{title}",
            modal : true,
            autoOpen : false,
            bgiframe : true,
            height : "600",
            width : "80%",
            open : function() {
                $(uiDialog).load(url);
            }
        };
    $(uiDialog).children().remove();
    //$(uiDialog).dialog("destroy");
    $(uiDialog).dialog(dialogOpts);
    $(uiDialog).dialog("open");
	
}


function contains( arreglo, valor){
	var i = arreglo.length;
	while (i--) {
	    if (arreglo[i] == valor) {
	      return true;
	    }
	}
	return false;
}

function openAddentityPopup(webpath, formType, fCallback, resultId, initialText){
	var url = webpath + "AddPopup.rasty?initialText=" + encodeURI(initialText) + "&formType=" + formType + "&onSuccessCallback=" + fCallback;
	var uiDialog = resultId;
	var dialogOpts = {
            //title : "{title}",
            modal : true,
            autoOpen : false,
            bgiframe : true,
            height : "600",
            width : "80%",
            open : function() {
                $(uiDialog).load(url);
            }
        };
    $(uiDialog).children().remove();
    //$(uiDialog).dialog("destroy");
    $(uiDialog).dialog(dialogOpts);
    $(uiDialog).dialog("open");
	
}

/* ausencias */
function borrarAusencia(link,oid, profesionalOid, onSuccess){
	jConfirm("Confirma borrar la notificación?", 'Confirmar', function(r) {
        if (r) {
        	$.ajax({
    		  	url: link+"?oid="+oid,
    		  	type: "GET",
    		  	datatype: "json",
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
    				
    				onSuccess(data, profesionalOid);
    		  	}
    		});        
            return false;
        } else {
            return false;
        }
    });
	
}

function closeAyuda(){
	
	$( "#ui-ayuda-turnos" ).hide();
}

function openAyuda(link, title ){
	
	var height = $(window).height()-20;
	var width = $(window).width()/2;

	/*
	$.ajax({
		  url: link,
		  type: "GET",
		  cache: false,
		  success: function(html){
		    
			$( "#ui-ayuda-turnos" ).html(html);
			$( "#ui-ayuda-turnos" ).height(height);
			$( "#ui-ayuda-turnos" ).width(width);
			$( "#ui-ayuda-turnos" ).css("top",0);
			$( "#ui-ayuda-turnos" ).css("left",0);
			$( "#ui-ayuda-turnos" ).show();
		  }
		});
	*/		
	gotoLinkPopup( link, "#ui-ayuda-turnos", title, height, width );
	
}