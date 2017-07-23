//WEBSOCKETS STUFF
$(document).ready(function() {

    connect();
    window.alert('Connected!');

    function connect(){
      var socket;
      var host = "ws://localhost:8000/chaptersweet/server/startDaemon.php";

      try{
        var socket = new WebSocket(host);

        message('<p class="event">Socket Status: '+socket.readyState);

        socket.onopen = function(){
          message('<p class="event">Socket Status: '+socket.readyState+' (open)');
        }

        socket.onmessage = function(msg){
          message('<p class="message">Received: '+msg.data);
        }

        socket.onclose = function(){
          message('<p class="event">Socket Status: '+socket.readyState+' (Closed)');
        } 

      } catch(exception){
        message('<p>Error'+exception);
      }

      function message(mymsg){
        $('#wrapper').append(msg+'</p>');
      }

    }//End connect

});