

// loads the jquery package from node_modules
var $ = require('jquery');
require('./jquery.collection.js');
require('simple-color-picker');

var baseUrl = '';

$(document).ready(function() {



    var Sortable = require('sortablejs');
    var el = document.getElementById('items');

    if(el){
        var sortable = Sortable.create(el, {
            onEnd: function(evt) {

                item = items[evt.oldIndex];
                previousItem = items[evt.newIndex];
                newPriority = previousItem.priority;
                // id of item to set new priority
                id = item.id;


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

    }



     $('#update_item_labels').collection({
        allow_up: false,
        allow_down: false,
        add_at_the_end: true
     });

//     $('#update_item_blockedBy').collection({
//             allow_up: false,
//             allow_down: false,
//             add_at_the_end: true
//          });

     var colorPicker = new ColorPicker();

//    var $collectionHolder;
//
//    // setup an "add a tag" link
//    var $addTagLink = $('<a href="#" class="add_tag_link">Add a label</a>');
//    var $newLinkLi = $('<li></li>').append($addTagLink);
//
//    jQuery(document).ready(function() {
//        // Get the ul that holds the collection of tags
//        $collectionHolder = $('ul.labels');
//
//        // add the "add a tag" anchor and li to the tags ul
//        $collectionHolder.append($newLinkLi);
//
//        // count the current form inputs we have (e.g. 2), use that as the new
//        // index when inserting a new item (e.g. 2)
//        $collectionHolder.data('index', $collectionHolder.find(':input').length);
//
//        $addTagLink.on('click', function(e) {
//            // prevent the link from creating a "#" on the URL
//            e.preventDefault();
//
//            // add a new tag form (see next code block)
//            addTagForm($collectionHolder, $newLinkLi);
//        });
//    });
//
//    function addTagForm($collectionHolder, $newLinkLi) {
//        // Get the data-prototype explained earlier
//        var prototype = $collectionHolder.data('prototype');
//
//        // get the new index
//        var index = $collectionHolder.data('index');
//
//        var newForm = prototype;
//        // You need this only if you didn't set 'label' => false in your tags field in TaskType
//        // Replace '__name__label__' in the prototype's HTML to
//        // instead be a number based on how many items we have
//        // newForm = newForm.replace(/__name__label__/g, index);
//
//        // Replace '__name__' in the prototype's HTML to
//        // instead be a number based on how many items we have
//        newForm = newForm.replace(/__name__/g, index);
//
//        // increase the index with one for the next item
//        $collectionHolder.data('index', index + 1);
//
//        // Display the form in the page in an li, before the "Add a tag" link li
//        var $newFormLi = $('<li></li>').append(newForm);
//        $newLinkLi.before($newFormLi);
//    }
//
//
//        // Get the ul that holds the collection of tags
//        $collectionHolder = $('ul.labels');
//
//        // add a delete link to all of the existing tag form li elements
//        $collectionHolder.find('li').each(function() {
//            addTagFormDeleteLink($(this));
//        });
//
//
//    function addTagForm() {
//        // ...
//
//        // add a delete link to the new form
//        addTagFormDeleteLink($newFormLi);
//    }
//
//    function addTagFormDeleteLink($tagFormLi) {
//        var $removeFormA = $('<a href="#">delete this label</a>');
//        $tagFormLi.append($removeFormA);
//
//        $removeFormA.on('click', function(e) {
//            // prevent the link from creating a "#" on the URL
//            e.preventDefault();
//
//            // remove the li for the tag form
//            $tagFormLi.remove();
//        });
//    }
});
