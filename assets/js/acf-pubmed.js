
jQuery(document).ready(function($){
    // make sure acf is loaded, it should be, but just in case
    if (typeof acf == 'undefined') { return; }
    
    $(document).on('change', '[data-key="field_physician_select_publications_pmid"] .acf-input input', function(e) {
        // we are not going to get the id of the input field
        // then retrieve the value to pass into the updated function
        var input = e.target.id;
        var value = document.getElementById(input).value;
        // console.log(input); // In case we need to test
        update_pubmed_on_state_change( input, value );
    });
    $(document).on('click', '[data-key="field_physician_select_publications_lookup"] .acf-input button', function(e) {
        // we are not going to get the id of the input field of the pmid
        // then retrieve the value to pass into the updated function
        pubmed_lookup_name = $(e.target).attr('name'); // get "name" of the button
        pubmed_lookup_pmid = pubmed_lookup_name.replace('field_physician_select_publications_lookup', 'field_physician_select_publications_pmid');
        var input = $("input[name='" + pubmed_lookup_pmid +"']").attr('id');    // #id of pmid input
        var value = $("input[name='" + pubmed_lookup_pmid +"']").val();         // value of the pmid input
        // console.log( input ); // In case we need to test
        update_pubmed_on_state_change( input, value );
    });
    $('[data-key="field_physician_select_publications_pmid"] .acf-input input').trigger('ready');
});
    
function update_pubmed_on_state_change(input, value) {
    if (this.request) {
        // if a recent request has been made abort it
        this.request.abort();
    }

    if (!value) {
        // no state selected
        // don't need to do anything else
        return;
    }
    
    // replace the pmid id with pubmed (textarea) id
    var pubmed_textarea = input.replace('field_physician_select_publications_pmid', 'field_physician_select_publications_pubmed');
    // console.log(pubmed_textarea); // In case we need to test
    document.getElementById(pubmed_textarea).value = 'Loading ...'; // Clear the value
    document.getElementById(pubmed_textarea).readonly = true; // Make sure the field is readonly
    
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
    this.request = jQuery.ajax({
        url: acf.get('ajaxurl'), // acf stored value
        data: data,
        type: 'post',
        dataType: 'json',
        success: function(json) {
            if (!json) {
                return;
            }

            console.log('ajax success');

            console.log(json['full']);

            // put the values into the fields
            if (json['full']) {
                // replace the textarea text with ajax results
                document.getElementById(pubmed_textarea).value = json['full'];
                document.getElementById(pubmed_textarea).readonly = true;
            }
        },
        error: function(jqXHR, textStatus, error) {
            document.getElementById(pubmed_textarea).value; // remove the value from the textarea
            alert (jqXHR+' : '+textStatus+' : '+error);
        }
    });
    
}
