

// loads the jquery package from node_modules
var $ = require('jquery');



$(document).ready(function() {
    var Sortable = require('sortablejs');
    var el = document.getElementById('items');

    var sortable = Sortable.create(el, {
        onEnd: function(evt) {
           // console.log(evt.oldIndex);
           // console.log(evt.newIndex);

            item = items[evt.oldIndex];
            previousItem = items[evt.newIndex];
            newPriority = previousItem.priority;
            // id of item to set new priority
            id = item.id;

            // todo url z configa
            $.ajax('http://127.0.0.1:8000/backlog/' + id + '/priority', {
                method: 'POST',
                data: {'priority': newPriority},
                complete: function(response, status){
                    //console.log(response);
                }
            })
        }
    }
    );
});
