function HistoriaAyudaItem(titulo, link){
	
	this.titulo = titulo;
    this.link = link;
	
}

function HistoriaAyuda(name){
    
	this.name = name;
	this.tituloIndice="";
	this.onclickIndice="";
	this.name = name;
    this.items = new Array();
    
    this.reset = function () {
    	this.items = new Array();
    };
    
    this.addIndice = function (titulo, onclick) {
    	this.tituloIndice = titulo;
    	this.onclickIndice = onclick;
    };
    
    this.addItem = function (item) {
    	var exists = false;
    	var i = 0;
    	while (i<this.items.length) {
    	    if (this.items[i].link == item.link) {
    	    	exists = true;
    	    	//quitamos los otros.
    	    	this.items = this.items.slice(0,i+1);
    	    }
    	    i++;
    	}
    	
    	if(!exists){
    		this.items[this.items.length] = item;
    	}
    	
    };
    
    this.armarLinksHistoria = function () {
    	var i = 0;
    	var strResult = "<ul class='historiaAyuda'>";
    	
    	if( this.tituloIndice.length>0){
    		strResult += "<li><a href=\"#\" onclick=\"" + this.onclickIndice + "()\" );return false;\">" + this.tituloIndice + "</a></li>";
    	}
    	
    	while (i < (this.items.length-1)) { //al último no lo mostramos.
    		link = this.items[i].link ;
    		titulo= this.items[i].titulo ;
    		strLink = "<li><a href=\"#\" onclick=\"openItemAyuda('" + titulo + "','" + link + "');return false;\">" + titulo + "</a></li>";
    	    strResult += strLink;
    		i++;
    	}
    	return strResult + "</ul>";
    }
    
}


function closeAyuda(){
	
	$( "#ui-ayuda" ).hide();
}

function openAyuda(link, title ){
	
	var height = $(window).height()-20;
	var width = $(window).width()/2;
		
	/*gotoLinkPopup( link, "#ui-ayuda", title, height, width );*/
	gotoLink( link, "_blank" );
	
}