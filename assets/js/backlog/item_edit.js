// loads the jquery package from node_modules
require('./../jquery.collection.js');
require('simple-color-picker');


$(document).ready(function() {

     $('#update_item_labels').collection({
        allow_up: false,
        allow_down: false,
        add_at_the_end: true
     });

});
