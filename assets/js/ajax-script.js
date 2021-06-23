$ = jQuery;
 
var paf = $("#provider-ajax-filter"); 
var pafForm = paf.find("form"); 
 
pafForm.submit(function(e){
    e.preventDefault(); 
 
    console.log("form submitted");

    if(pafForm.find("#region").val().length !== 0) {
        var region = pafForm.find("#region").val();
    }
    if(pafForm.find("#title").val().length !== 0) {
        var title = pafForm.find("#title").val();
    }
    if(pafForm.find("#providers").val().length !== 0) {
        var providers = pafForm.find("#providers").val();
    }
     
    // console.log(title);

    $.ajax({
        type: 'POST',
        url: '/wp-admin/admin-ajax.php',
        dataType: 'html',
        data: {
            action : "provider_ajax_filter",
            region : region,
            title : title,
            providers : providers
        },
        success: function(res) { 
          $('#providers .more').remove();
          $('.card-list-doctors').html(res);
        }
      });
 
});