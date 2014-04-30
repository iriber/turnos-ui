
function wait( id ){
	$( id ).fadeTo("fast", 0.33);
}

function wakeUp( id ){
	$( id ).fadeTo("fast", 1);
}



function jAlertConfirm( title, text, fCallback, attr, icon ){
	
	if( icon == undefined )
		icon = "ui-icon-alert";
	
	//creamos el div donde vamos a escribir el popup
	//agregamos el div para el dialogo si es que no existe.
	if (!$("#dialog-confirm").length) {
		$("body").append("<div id='dialog-confirm'></div>");
		
	}
	$("#dialog-confirm").html("<p><span class='ui-icon " + icon  + "' ></span>" + text +  "</p>");
	
	$( "#dialog-confirm" ).dialog({
		 /*resizable: true,*/
		 /*height:140,*/
		 title: title,
		 modal: true,
		 buttons: {
			 "Si": function() {
				 $( this ).dialog( "close" );
				 fCallback( attr );
			 },
			 "No": function() {
				 $( this ).dialog( "close" );
			 }
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


function gotoLink( link, target ){
	
	if( target != undefined )
		window.open(link, target);
	else
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
    //$(uiDialog).dialog("destroy");
    $(uiDialog).dialog(dialogOpts);
    $(uiDialog).dialog("open");
}

/* FINDER */
function closeFinderPopup(resultId){
	var uiDialog = resultId;
	$(uiDialog).children().remove();
    $(uiDialog).dialog("destroy");
    
}
function openFinderPopup(webpath, filterType, fCallback, resultId, initialText, title, extraParams, height, width, popupDivId, onAddCallback, labelAdd){
	
	if ( width == undefined )
		width = "80%";

	if ( height == undefined )
		height = "600";
	
	
	//var url = webpath + "AddPopup.rasty?initialText=" + encodeURI(initialText) + "&formType=" + formType + "&popupDivId=" + popupDivId + "&onSuccessCallback=" + fCallback + "&" + extraParams;
	var url = webpath + "FinderPopup.rasty?filterType=" + filterType + "&onSelectCallback=" + fCallback  + "&popupDivId=" + popupDivId+ "&" + extraParams;;

	if ( onAddCallback != undefined )
		url += "&hasAddEntity=1&onClickAddCallback=" + encodeURI(onAddCallback) +"&msgAdd=" + encodeURI(labelAdd);
	else
		url += "&hasAddEntity=0";

	if( initialText != undefined )
		url += "&initialText="+ encodeURI(initialText);
	
	console.log("openFinderPopup: " + url);

	var uiDialog = resultId;
	var dialogOpts = {
            title : title,
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

function openAddentityPopup(webpath, formType, fCallback, resultId, initialText, title, extraParams, height, width, popupDivId){
	
	if ( width == undefined )
		width = "80%";

	if ( height == undefined )
		height = "600";
	
	var url = webpath + "AddPopup.rasty?initialText=" + encodeURI(initialText) + "&formType=" + formType + "&popupDivId=" + popupDivId + "&onSuccessCallback=" + fCallback + "&" + extraParams;
	console.log("openAddentityPopup: " + url);
	var uiDialog = resultId;
	var dialogOpts = {
            title : title,
            modal : true,
            autoOpen : false,
            bgiframe : true,
            height : height,
            width : width,
            open : function() {
                $(uiDialog).load(url);
            }
        };
	
	$(uiDialog).children().remove();
    //$(uiDialog).dialog("destroy");
    $(uiDialog).dialog(dialogOpts);
    $(uiDialog).dialog( "option", "draggable", true );
    //$( ".selector" ).dialog( "option", "draggable", false );
    $(uiDialog).dialog("open");
	
}

function closePopup(resultId){
	var uiDialog = resultId;
	$(uiDialog).children().remove();
    $(uiDialog).dialog("destroy");
    
}
