<html>
    <head>
	<title>Websocket Test </title>
    </head>
    <body>

	Websocket Test
	<div id="root"></div>
	<input id ="chat" type="text" onkeydown="handlekey(this)" />

	<script>
	 function guid() {
	     function s4() {
		 return Math.floor((1 + Math.random()) * 0x10000)
			    .toString(16)
			    .substring(1);
	     }
	     return s4();
	 }

	 var host = 'ws://10.125.194.162:8080/';
	 var root = document.getElementById("root");
	 let uuid = guid();
	 var socket = new WebSocket(host);
	 socket.onmessage = function(e) {
	     try{
		 let res = JSON.parse(e.data);
		 document.getElementById('root').innerHTML +="<br />"+ res.id +":"+res.msg;
	     }catch(ex){
	     }
	 };

	 function handlekey(el){
	     if(event.key === "Enter"){
		 let val = el.value;
		 el.value = '';
		 let packet = {id: uuid, msg: val};
		 socket.send(JSON.stringify(packet));
	     }
	 }


	 /*
	    socket.onerror = function(e) {
            console.warn('Error FE', e)
	    };*/

	 socket.onopen = function(ev) {
	     let packet = {id: uuid, msg: "is active"};
	     socket.send(JSON.stringify(packet));
	     console.log("Socket opened...");
	 };

	 //Error
	 socket.onerror = function (ev) {
	     root.append("Error: "+ev.toString());
	     console.log('Error ', ev);
	 };

	</script>
    </body>
</html>
