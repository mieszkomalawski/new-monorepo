

// loads the jquery package from node_modules
var $ = require('jquery');
require('./../../bower_components/remarkable-bootstrap-notify/bootstrap-notify');


//lancement serveur chat
function NotifServer(){
    notif = new WebSocket("ws://127.0.0.1:8080");

    notif.onmessage = function (event) {
        console.log(event.data);
        var notify = $.notify(event.data, { allow_dismiss: true });

    }

    notif.onopen = function() {

        console.log('open');
    }

    notif.onerror = function(error) {
        console.log(error);
    }


}

NotifServer();