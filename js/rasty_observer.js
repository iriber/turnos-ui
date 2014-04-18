/**
 * Este script lo definie rasty para implementar el patrón
 * Observer con el fin de mantener comunicados los componentes
 * de una misma página. Por ejemplo, si componemos una página
 * con 3 componentes, comp1, comp2 y comp3. Supongamos que comp1
 * muestra información de una persona, comp2 muestra sus deudas, y
 * comp3 modifica los datos de una persona. Nos interesaría que cuando
 * comp3 modifica los datos, comp1 se entere y actualice lo que muestra.
 */

/**
 * evento que indica un cambio en un componente.
 * @param name indica el nombre del componente que produce el evento
 * @param data información que genera el evento
 * @param type tipo de evento (podríamos indicar la clase del model que cambió)
 * @returns {Event}
 */
function Event(component, data, type){
	
	this.component = component;
	this.data = data;
	this.type = type;
}

/**
 * Subject es el responsable de notificar los cambios
 * @param name
 * @returns {Subject}
 */
function Subject(name){
    
	this.name = name;
    this.observers = new Array();
    
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
		
		var rastyEvent = new Event(jsonMsg["from"], jsonMsg["params"], jsonMsg["eventType"]);
	
		subject.notify( rastyEvent );

    };
}

/**
 * Observer es quien recibe las notificaciones de cambios.
 * También le avisa al subject ante un cambio.
 * @param name
 * @param callback
 * @returns {Observer}
 */
function Observer(name, callback){
    
	this.name = name;
    this.callback = callback;
    this.subject = undefined;
    
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
}


/**
 * ejemplo:
 *
 *
 
 $(document).ready(function(){

	subject = new Subject("login");

	observer1 = new Observer("boton1", funcionBoton1);
	observer2 = new Observer("boton2", funcionBoton2);

	subject.addObserver( observer1 );
	subject.addObserver( observer2 );

});

function doTest(){
	event = new Event("obrasocialchange");
	
	observer1.change(event) ;
}

function funcionBoton1(event){
	alert("soy la función del botón 1. Event: " + event.name);
}
function funcionBoton2(event){
	alert("soy la función del botón 2. Event: " + event.name);
}

 *
 **/