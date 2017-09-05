

// loads the jquery package from node_modules
var $ = require('jquery');

var baseUrl = '';

$(document).ready(function() {
    var Sortable = require('sortablejs');
    var el = document.getElementById('items');

    var sortable = Sortable.create(el, {
        onEnd: function(evt) {

            item = items[evt.oldIndex];
            previousItem = items[evt.newIndex];
            newPriority = previousItem.priority;
            // id of item to set new priority
            id = item.id;

            // todo url z configa
            $.ajax(baseUrl + '/backlog/' + id + '/priority', {
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
