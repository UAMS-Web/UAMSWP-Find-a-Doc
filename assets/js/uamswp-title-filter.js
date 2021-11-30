jQuery(function($) {
    // -- Used in title only & region filter --//
    $(document).ready(ajaxHideContent);
    $(window).resize(ajaxHideContent);
    function ajaxHideContent(){
        // Find the height of the relevant elements
        var $providerLoadMore = $('#providers .ajax-filter-load-more').outerHeight();
        var $providerCardHeight = $('#providers .card:first-child').outerHeight() + 15;
        var $providerCardListHeight = $('#providers .card-list').outerHeight() - 15;
        // console.log($providerCardListHeight);
        var $providerHideContentHeight = $providerCardHeight + ($providerLoadMore * 0.9);

        // Set the max-height of the card list container based on that math
        $('#providers.hideContent .card-list-container').css("max-height", $providerHideContentHeight);
        // Add / remove overflow class based on math
        if ($providerCardListHeight > $providerCardHeight) {
            $("#providers").addClass("overflow");
        } else {
            $("#providers").removeClass("overflow");
        }
    }

    var paf = $("#provider-title-ajax-filter"); 
    var pafForm = paf.find("form");

    pafForm.find('select').on('change', function(){
        pafForm.submit();
    });

    pafForm.find("#provider_clear").on('click', function(e){
        e.preventDefault(); 
     
        console.log("form cleared");

        pafForm.find("#provider_title").prop('selectedIndex', 0);

        pafForm.submit();

    });

    pafForm.submit(function(e){
        e.preventDefault(); 
     
        console.log("form submitted");
    
        if(null != pafForm.find("#provider_title").val() && pafForm.find("#provider_title").val().length !== 0) {
            var title = pafForm.find("#provider_title").val();
        }
        if(pafForm.find("#providers-ids").val().length !== 0) {
            var providers = pafForm.find("#providers-ids").val();
        }

        var region = '';
    
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            dataType: 'html',
            data: {
                action : "provider_title_ajax_filter",
                provider_region : region,
                provider_title : title,
                providers : providers
            },
            beforeSend : function ( xhr ) {
                $('.card-list-doctors .card').css('transition', '0.3s').css('opacity', '0');
                $('#providers .ajax-filter-load-more').css('transition', '0.3s').css('opacity', '0');
                $('.card-list-doctors').fadeIn(300, function() { $(this).before('<div id="ajax-loading-doctors" class="ajax-loading">Loading...</div>'); });
            },
            success : function(res) { 
                $('.card-list-doctors').html(res);  
            },
            complete : function () {
                $('#ajax-loading-doctors').remove();
                $('.card-list-doctors').css('transition', '1s').css('opacity', '1');
                $('#providers .ajax-filter-load-more').css('transition', '1s').css('opacity', '1');
                setTimeout(function() {
                    $('.card-list-doctors').removeAttr( "style" );
                }, 1001);
                ajaxHideContent();
            },
        });
    });
});
jQuery(document).ready(function($){
    // -- Used in title only & region filter --//
    // Make the Load More button do things
    $(".ajax-filter-load-more button").on("click", function() {
        var $providerCardListHeight = $('#providers .card-list').outerHeight() - 15;
        // Transition the container
        $("#providers .card-list-container").css("max-height", $providerCardListHeight);
        $("#providers").addClass("expanded");
        //$("#providers .card-list-container").removeAttr( "style" );
        // Set max-height to none and remove transition after transition
        setTimeout(function() {
            // $("#providers.expanded .card-list-container").css("max-height", "none").css("transition", "none");
            $("#providers.expanded .card-list-container").css("max-height", "none");
        }, 1001);
    });
    if( $('#providers').length ) {
        // Set the load more background colors based on the section's background color
        var $ajaxSectionBGColor = $( "#providers" ).css( "background-color" ).replace("rgb(", "").replace(")", '');
        $( "#providers .ajax-filter-load-more" ).css( "background-image", "linear-gradient(180deg, rgba(" + $ajaxSectionBGColor + ",0) 0%, rgba(" + $ajaxSectionBGColor + ",1) 75%)" );
        $( "#providers .ajax-filter-load-more .btn" ).css( "box-shadow", "0 0 1rem 1rem rgb(" + $ajaxSectionBGColor + ",0.5)" );
    }
});