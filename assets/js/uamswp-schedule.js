jQuery(function($) {

    var saf = $("#scheduling"); 
    var safForm = saf.find("form");

    safForm.find('select').on('change', function(){
        safForm.submit();
    });

    safForm.submit(function(e){
        e.preventDefault(); 
     
        console.log("form submitted");
    
        if(null != safForm.find("#schedule_options").val() && safForm.find("#schedule_options").val().length !== 0) {
            var schedule_options = safForm.find("#schedule_options").val();
        }
        if(safForm.find("#pid").val().length !== 0) {
            var pid = safForm.find("#pid").val();
        }
    
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            dataType: 'html',
            data: {
                action : "schedule_ajax_filter",
                pid : pid,
                schedule_options : schedule_options,
            },
            success : function(res) { 
                $('.mychart-scheduling').html(res);  
            },
        });
    });
});