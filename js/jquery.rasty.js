/*
 * rasty for jQuery UI

 *
 * Copyright (c) 2014 Bernardo Iribarne
 * Dual licensed under the MIT license.
 *  - http://www.opensource.org/licenses/mit-license.php
 *
 * Author: Bernardo Iribarne
 * Version: 0.0.1
 */

(function($) {

	/**
	 * creamos el componente
	 * 
	 * options:
	 * 	- name: nombre del componente rasty
	 *  - webPath: web path de la app
	 *  - params: parámetros para el componente
	 *  - showRefresh: si se muestra o no el botón para actualizar el componente
	 */
    $.fn.rasty = function (options) {
       
    	if(options.name == undefined )
    		return;
    	if(options.webPath == undefined )
        	return;
        		
    	var me = this;
    	
    	/*
    	 * nombre del componente a invocar
    	 */
    	this.name = options.name;
    	/*
    	 * web path del comonente
    	 */
    	this.webPath = options.webPath;
    	
    	/*
    	 * indica si se muestra el botón para actualizar
    	 * el contenido.
    	 */
    	this.showRefresh = false;
    	if(options.showRefresh != undefined ){
    		this.showRefresh = options.showRefresh;
    	}
    	
    	/*
    	 * parámetros del componente
    	 */
    	this.params = new Array();
    	if(options.params != undefined ){
    		this.params = options.params;
    	}

    	/*
    	 * título.
    	 * si se indica el título, mostramos el componente
    	 * contenido en un fieldset con el título como legend.
    	 */
    	this.title = false;
    	if(options.title != undefined ){
    		this.title = options.title;
    	}
    	
    	
        this.container = this;

        //inicializamos el componente rasty
        //es el encargado de obtener el contenido del componente
        //esto lo hace mediante ajax.
        this.rasty_component = new $.fn.rasty.RastyComponent(this.name, this.webPath, this.params);

        //agregamos el contenido del componente
        this.content = this.rasty_component.object();
        
        this.fieldset = false;
        
        /*
         * eventos sobre los cuales está interesado
         * el componente para el observer.
         */
        this.interestedEventTypes = new Array();
        if(options.interestedEventTypes != undefined ){
    		this.interestedEventTypes = options.interestedEventTypes;
    	}
        this.addInterestedEventType = function(eventType){
        	me.interestedEventTypes[me.interestedEventTypes.length] = enventType; 
        }
        
        /**
         * se inicializa el componente.
         */
        this.createui= function()
        {
            if(me.title){
            	
            	//le agrego en un fieldset para mostrar el title.
            	fieldset = $("<fieldset>").append(
            					$("<legend>").html( me.title )
            				);
            	
            	me.fieldset = fieldset;
            	
            }

            if( me.showRefresh ){
            	
                //agregamos un botón para actualizar el componente.
                $("<div>", { 'class': "rasty_refresh rasty_common rasty_button"})
                            .click(function(){me.load(); return false;})
                            .appendTo(me);
            	
            }
            
        };
        
        /**
         * una vez que se carga el componente rasty
         * invocamos a esta función para mostrar el contenido
         * 
         * @param {html dom} content contenido del componente rasty.
         */
        this._rastyLoaded= function(content) {
        	
        	me.container.empty();
        	me.createui();
        	
        	if(me.title){ //va dentro del fieldset
        		content.prependTo(me.fieldset);
        		me.container.prepend(fieldset);
        	}
        	else{
        		content.prependTo(me.container);
        	}
        	
        };


        /**
         * se realiza el llamado ajax para obtener el 
         * contenido del componente.
         *
         */
        this.load= function()
        {
        	//agregamos info para que se visualize
        	//que el componente se está cargando
        	me.wait();

        	//cargamos el componente.
            me.rasty_component.load( 
            		
            		function(content) {//on success.
		            	me._rastyLoaded(content);
		            
		            }, function() { //on error
		                
		            	
		            	
		            }, function(){ //on complete
		            	
		            	me.wakeUp();
		            });
        };
        
        /**
         * se elimina el componente rasty.
         */
        this.destroy= function() {
        	alert("TODO destroy");
        };
        
        this.wait =function ( ){
        	me.addClass("rasty_loading");
        	me.fadeTo("fast", 0.33);
        }

        this.wakeUp=function( ){
        	me.removeClass("rasty_loading");
        	me.fadeTo("fast", 1);
        }

        /**
         * se setea un parámetro al componente.
         */
        this.setParam=function( name, value ){
        	
        	me.params[name] = value;
        	
        	//actualizamos los parámetros del componente.
        	me.rasty_component._params = me.params;
        }
        
        /**
         * se obtiene el valor de un parámetro del componente.
         */
        this.getParam=function( name ){
        	me.params[name] = value;
        }
        
        /**
         * callback para el observer
         */
        this.observerCallback = function( event ){
        	
        	//chequeamos si el evento que ocurrió nos interesa.
			if( contains( me.interestedEventTypes, event.type) ){
				
				//seteamos los parámetros en el componente.
				for ( var key in event.data) {
					me.setParam(key, encodeURI(event.data[key]) );
				} 
				//cargamos el componente.
				me.load();
			}
        }
       
        //inicializamos el observer
        this.rastyObserver = new $.fn.rasty.RastyObserver(this.name, this.observerCallback);
        
        //inicializamos el componente.
        this.createui();
        
        return this;
    };//en $.fn.rasty
    

    /**
     * @class $.fn.rasty.RastyComponent 
     * @constructor
     * @param {string} name Nombre del componente
     * @param {string} webPath web path para invocar al componente
     * @param {array} params Parámetros para el componente
     */
    $.fn.rasty.RastyComponent = function(name, webPath, params) {
        this._loaded = false;
        this._name = name;
        this._webPath = webPath;
        this._params = params;
        
        this._content = $("<span>").html("");
    };


    /** @lends $.ui.rasty.RastyComponent.prototype */
    (function() {
    	
        /**
         * Restore initial object state.
         *
         */
        this._reset = function() {
        	//TODO
        };

        /**
         * Check if component is loaded.
         *
         * @return {boolean}
         */
        this.loaded = function() { return this._loaded; };

        /**
         * Load rasty component.
         *
         * @param {name} name nombre del componente.
         * @param {Function=} loaded Function will be called on component load.
         */
        this.load = function( onSuccess, onError, onComplete ) {
            var self = this;

            onSuccess = onSuccess || jQuery.noop;
            this._loaded = false;

            //llamar por ajax para obtener el contenido del componente.
            //y lo cargamos en this.object
            
            //var url = "http://localhost/turnos_ui/AgendaTurnos.rasty";
            var strParams = ""; 
			for ( var key in this._params) {
				strParams = strParams + key + "=" + encodeURI(this._params[key]) + "&";
			}
			var url = this._webPath + this._name + ".rasty?"+ strParams;
            $.ajax({
    		  	url: url ,
    		  	type: "GET",
    		  	cache: false,
    			complete:function(){

            		onComplete();
    			},
    		  	success: function(content){
    				this._content = $("<span>").html(content);
    				onSuccess( this._content );
    		  	},
    		  	error: function(content){
    		  		this._content = $("<span>").html(content);	  	
    				onError(this._content);
    		  	}
            });
            
            
        };
        
        
        this.object = function(){
        	return this._content;
        }


    }).apply($.fn.rasty.RastyComponent.prototype);
    
    
/**
 * Lo siguiente es para implementar el patrón
 * Observer con el fin de mantener comunicados los componentes
 * de una misma página. Por ejemplo, si componemos una página
 * con 3 componentes, comp1, comp2 y comp3. Supongamos que comp1
 * muestra información de una persona, comp2 muestra sus deudas, y
 * comp3 modifica los datos de una persona. Nos interesaría que cuando
 * comp3 modifica los datos, comp1 se entere y actualice lo que muestra.
 */


    
    /**
     * @class $.fn.rasty.RastyObserver Listener para el patrón observer.
     * @constructor
     * @param {string} name Nombre del observer
     * @param {Function=} callback Función a invocar para avisarle al observer que hubo un cambio
     */
    $.fn.rasty.RastyObserver = function( name, callback) {
        
    	this.name = name;
        this.callback = callback;
        this.subject = undefined;
    };


    /** @lends $.fn.rasty.RastyObserver.prototype */
    (function() {
    	
    	/**
         * función que ejecuta el subject para notifiar
         * un cambio. 
         */
        this.update = function (event, from) {
        	
        	if( from != this)
        		this.callback(event); 
        };
        
        /**
         * se setea el subject para infomar 
         * cambios propios.
         */
        this.setSubject = function(aSubject){
        	this.subject = aSubject;
        }
        
        /**
         * ante un cambio propia, lo notifica al subject.
         */
        this.change = function(event){
        	this.subject.notify(event, this);
        }
        
        /**
         * función a llamar cuando recibe la notificación
         * de un cambio.
         */
        this.setCallback = function(aCallback){
        	this.callack = aCallback;
        }
        
    }).apply($.fn.rasty.RastyObserver.prototype);
    
    /**
     * @class $.fn.rasty.RastyEvent Evento para los cambios del patrón observer.
     * @constructor
     * @param {string} componente Nombre del componente que dispara el evento
     * @param {array} data información del evento
     * @param {string} type tipo de evento
     * 
     */
    $.fn.rasty.RastyEvent = function(component, data, type){
    	
    	this.component = component;
    	this.data = data;
    	this.type = type;
    };


    /**
     * @class $.fn.rasty.RastySubject Subject para el patrón observer.
     * @constructor
     * @param {string} componente Nombre del componente que dispara el evento
     * @param {array} data información del evento
     * @param {string} type tipo de evento
     * 
     */
    $.fn.rasty.RastySubject = function(name){
    	
    	this.name = name;
    	this.observers = new Array();
    };


    /** @lends $.fn.rasty.RastySubject.prototype */
    (function() {
    	/**
         * agrega un observer a su lista de listener
         */
        this.addObserver = function (observer) {
        	var exists = false;
        	var i = this.observers.length;
        	while (i--) {
        	    if (this.observers[i].name == observer.name) {
        	    	exists = true;
        	    }
        	}
        	
        	if(!exists){
        		//alert("agregando observer " + observer.name);
        		this.observers[this.observers.length] = observer;
            	observer.setSubject(this);	
        	}
        	
        };
        
        /**
         * notifica a todos los observes sobre un
         * evento
         */
        this.notify = function (event, from) {
        	
        	console.log("observers from:" + from + " event:" + event.type);
        	
        	for ( var int = 0; int < this.observers.length; int++) {
        		console.log("alertando a " + this.observers[int].name);
        		(this.observers[int]).update(event, from);
        	}
        	
        	//notificamos al servidor.
        	//armos el mensaje para cose-observer.
    		//appObserver.send(event.data, event.type);
        	
        	
        };
        
        /**
         * recibe notificaciones del subject de cose (websocket)
         * arma un evento rasty y lo notifica a los subscriptores.
         */
        this.notifyServerChanges = function(e){
        	
    		//armamos el evento Rasty
        	console.log("receiving..."+e.data);
    		var jsonMsg = $.parseJSON(e.data) ;
    		
    		var rastyEvent = new $.fn.rasty.RastyEvent(jsonMsg["from"], jsonMsg["params"], jsonMsg["eventType"]);
    	
    		subject.notify( rastyEvent );

        };        
    }).apply($.fn.rasty.RastySubject.prototype);

    /**
     * ejemplo de cómo utilizar el patrón observer:
     *
     *
        
        $(document).ready(function(){

       	subject = new $.fn.rasty.RastySubject("login");

       	observer1 = new $.fn.rasty.RastyObserver("boton1", funcionBoton1);
       	observer2 = new $.fn.rasty.RastyObserver("boton2", funcionBoton2);

       	subject.addObserver( observer1 );
       	subject.addObserver( observer2 );

       });

       function doTest(){
       	event = new $.fn.rasty.RastyEvent("obrasocialchange");
       	observer1.change(event) ;
       }

       function funcionBoton1(event){
       	alert("soy la función del botón 1. Event: " + event.name);
       }
       function funcionBoton2(event){
       	alert("soy la función del botón 2. Event: " + event.name);
       }


    el ejemplo es para cuando se quiere realizar algo personalizado
    ya que rasty por default ya inicializa el patrón observer según
    la configuración de cada componente:
    
      1- La página crea el subject
      2- cuando creamos un componente redefinimos el método initObserverEventType
      donde indicamos los tipos de eventos que nos interesan:
     
      		protected function initObserverEventType(){

   				$this->addEventType( "Turno" );
   				$this->addEventType( "Profesional" );
   				$this->addEventType( "TipoAgenda" );
   			}
   	entonces cuando se renderiza el componente, rasty crea el observer para 
   	el componente teniendo en cuenta dichos tipos de eventos.
   */
})(jQuery);



