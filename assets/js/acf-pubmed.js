
jQuery(document).ready(function($){
    // make sure acf is loaded, it should be, but just in case
    if (typeof acf == 'undefined') { return; }
    
    $(document).on('change', '[data-key="field_physician_select_publications_pmid"] .acf-input input', function(e) {
        // we are not going to do anyting in this anonymous function
        // the reason is explained below
        // call function, we need to pass the event and jQuery object
        update_pubmed_on_state_change(e, $);
    });
    $('[data-key="field_physician_select_publications_pmid"] .acf-input input').trigger('ready');
});
    
function update_pubmed_on_state_change(e, $) {
    if (this.request) {
        // if a recent request has been made abort it
        this.request.abort();
    }

    // get the target of the event and then get the value of that field
    var input = $(e.target);
    var value = input.val();

    if (!value) {
        // no state selected
        // don't need to do anything else
        return;
    }
    
    // get the city select field, and remove all exisiting choices
    var pubmed_textarea = input.attr('id').replace('field_physician_select_publications_pmid', 'field_physician_select_publications_pubmed');
    pubmed_textarea = $('textarea#' +pubmed_textarea); // Set textarea id
    // console.log(pubmed_textarea);
    pubmed_textarea.val('Loading ...'); // Clear the value
    pubmed_textarea.attr('readonly', true);
    
    // set and prepare data for ajax
    var data = {
        action: 'load_content_from_pubmed',
        pmid: value
    }
    
    // call the acf function that will fill in other values
    // like post_id and the acf nonce
    data = acf.prepareForAjax(data);
    
    // make ajax request
    // instead of going through the acf.ajax object to make requests like in <5.7
    // we need to do a lot of the work ourselves, but other than the method that's called
    // this has not changed much
    this.request = $.ajax({
        url: acf.get('ajaxurl'), // acf stored value
        data: data,
        type: 'post',
        dataType: 'json',
        success: function(json) {
            if (!json) {
                return;
            }

            console.log('ajax success');

            //console.log(json);

            // put the values into the fields
            if (json['full']) {
                // data-key == field key of textarea field
                $( pubmed_textarea ).val(json['full']);
                $( pubmed_textarea ).attr('readonly', true);
            }
        },
        error: function(jqXHR, textStatus, error) {
            alert (jqXHR+' : '+textStatus+' : '+error);
        }
    });
    
}
// Original JS below
// jQuery(document).ready(function($){
// 	// make sure acf is loaded, it should be, but just in case
// 	if (typeof acf == 'undefined') { return; }

// 	// extend the acf.ajax object
// 	// you should probably rename this var
// 	var pubmedExtension = acf.Model.extend({
// 		events: {
// 			// this data-key must match the field key for the input field that
// 			// will trigger the change of the other fields. In this case, the input field
// 			// is a text field and we need to set an event and get the value.
// 			// When ACF updates the value it triggers a change action on a different t
// 			// extarea field than the one that will contain the value
// 			'change [data-key="field_physician_select_publications_pmid"] .acf-input input': '_text_change',
// 		},

// 		_text_change: function(e) {

// 			// get which input changed
// 			var $input = e.$el;
// 			// create the target id
// 			var $target = $input.attr('id').replace('field_physician_select_publications_pmid', 'field_physician_select_publications_pubmed');

// 			//console.log($input);
// 			//console.log('textarea#' +$target);

// 			// clear existing values from the fields we will update
// 			// clear input field
// 			// targer == field key of textarea field
// 			$( 'textarea#' +$target ).val('');

// 			// get the input value
// 			var $value = e.$el.val();

// 			// if there is no value, exit
// 			if (!$value) {
// 				return;
// 			}

// 			//console.log('started ' + $value);

// 			// now we can do our ajax request

// 			// I assume this tests to see if there is already a request
// 			// for this and cancels it if there is
// 			if( this.request) {
// 				this.request.abort();
// 			}

// 			// I don't know exactly what it does
// 			// acf does it so I copied it
// 			var self = this,
// 			    data = this.o;

// 			// set the ajax action that's set up in php
// 			data.action = 'load_content_from_pubmed';
// 			// set the term id value to be submitted
// 			data.pmid = $value;

// 			// this is another bit I'm not sure about
// 			// copied from ACF
// 			data.exists = [];

// 			//console.log( acf.get('ajaxurl') );

// 			// this the request is copied from ACF
// 			this.request = $.ajax({
// 				url:		acf.get('ajaxurl'),
// 				data:		acf.prepareForAjax(data),
// 				type:		'post',
// 				dataType:	'json',
// 				async: true,
// 				success: function(json){

// 					if (!json) {
// 						return;
// 					}

// 					console.log('ajax success');

// 					//console.log(json);

// 					// put the values into the fields
// 					if (json['full']) {
// 						// data-key == field key of textarea field
// 						// originally '[data-key="field_592437c881851"] textarea'
// 						$( 'textarea#' +$target ).val(json['full']);
// 					}
// 				},
// 				error: function(jqXHR, textStatus, error) {
// 					alert (jqXHR+' : '+textStatus+' : '+error);
// 				}
// 			});
// 		},
// 	});

// });
