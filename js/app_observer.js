/**
 *  App observer.
 *  
 *  Definimos un cliente para observar los cambios en el servidor.
 *  Esto lo hacemos a través de websocket.
 *  
 *  La idea es observar el modelo en el servidor para saber cuándo
 *  se producen cambios.
 *  
 */


var conn=null;


function AppObserver(url, port){

	this.server = null ;
	this.url = url;
	this.port = port;
	
	this.send = function( data, eventType){

		var message = new Object();
		message.type = "model-change";
		message.params = data;
		message.eventType = eventType;
		
		var jsonMessage = JSON.stringify(message, "\t");
		console.log( "sending..." + jsonMessage);
		
		if(conn!=null)
		conn.send( jsonMessage );
	};
	
	this.subscribeOn = function(eventType){

		var message = new Object();
		message.type = "subscription";
		message.eventType = eventType;
		var jsonMessage = JSON.stringify(message, "\t");

		conn.send( jsonMessage );
		
		this.server.send( message );

	};

	this.unsubscribeOn = function(eventType){

		var message = new Object();
		message.type = "unsubscription";
		message.eventType = eventType;
		var jsonMessage = JSON.stringify(message, "\t");

		conn.send( jsonMessage );

	};
	

	this.listen = function( eventType,onMessageCallback){
		
		if( conn == null )
			conn = new WebSocket('ws://' + this.url + ':' + this.port);

		
		conn.onopen = function(e) {
		  
			console.log("Connection to websocket " + this.url + " established!");
		
			var message = new Object();
			message.type = "subscription";
			message.eventType = eventType;
			var jsonMessage = JSON.stringify(message, "\t");
			console.log("enviando onopen... " + jsonMessage);
			conn.send( jsonMessage );
		};

		conn.onmessage = function(e) {
		    
			console.log("receive message " + e.data );

			onMessageCallback(e);
		};
		
		this.server= conn;			
	};
}