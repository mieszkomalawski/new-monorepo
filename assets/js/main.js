

// loads the jquery package from node_modules
var $ = require('jquery');


//lancement serveur chat
function NotifServer(){
    notif = new WebSocket("ws://127.0.0.1:8080");

    notif.onmessage = function (event) {
        console.log(event.data);

    }

    notif.onopen = function() {

        console.log('open');
    }

    notif.onerror = function(error) {
        console.log(error);
    }


}

NotifServer();