jQuery(function($) {

    var saf = $("#scheduling"); 
    var safForm = saf.find("form");

    safForm.find('select').on('change', function(){
        safForm.submit();
    });

    safForm.submit(function(e){
        e.preventDefault(); 
     
        if(null != safForm.find("#schedule_options").val() && safForm.find("#schedule_options").val().length !== 0) {
            var schedule_options = safForm.find("#schedule_options").val();
        }
        if(safForm.find("#pid").val().length !== 0) {
            var pid = safForm.find("#pid").val();
        }
    
        var nonce = (typeof uamswp_ajax_scripts !== 'undefined' && uamswp_ajax_scripts.security) ? uamswp_ajax_scripts.security : '';
        var ajax_url = (typeof uamswp_ajax_scripts !== 'undefined' && uamswp_ajax_scripts.ajaxurl) ? uamswp_ajax_scripts.ajaxurl : '/wp-admin/admin-ajax.php';

        $.ajax({
            type: 'POST',
            url: ajax_url,
            dataType: 'html',
            data: {
                action : "schedule_ajax_filter",
                security : nonce,
                pid : pid,
                schedule_options : schedule_options,
            },
            success : function(res) { 
                $('.mychart-scheduling').html(res);  
            },
        });
    });
});