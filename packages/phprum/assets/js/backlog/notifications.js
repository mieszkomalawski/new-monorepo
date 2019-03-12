require('../../../bower_components/remarkable-bootstrap-notify/bootstrap-notify');


//lancement serveur chat
function NotifServer(){
    notif = new WebSocket("ws://" + settings.wsHost + ":" + settings.wsPort);

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