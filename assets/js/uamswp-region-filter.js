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
    
    var paf = $("#provider-ajax-filter"); 
    var pafForm = paf.find("form");

    var laf = $("#location-ajax-filter"); 
    var lafForm = laf.find("form");

    pafForm.find('select').on('change', function(){
        pafForm.submit();
    });

    lafForm.find('select').on('change', function(){
        lafForm.submit();
    });

    pafForm.find("#provider_clear").on('click', function(e){
        e.preventDefault(); 
        
        console.log("form cleared");

        pafForm.find("#provider_region").prop('selectedIndex', 0);
        pafForm.find("#provider_title").prop('selectedIndex', 0);

        lafForm.find("#location_region").prop('selectedIndex', 0);
        
        deleteCookie('wp_filter_region');
        // deleteCookie('_provider_title');

        pafForm.submit();
        lafForm.submit();

    });

    lafForm.find("#location_clear").on('click', function(e){
        e.preventDefault(); 
        
        console.log("form cleared");

        pafForm.find("#provider_region").prop('selectedIndex', 0);

        lafForm.find("#location_region").prop('selectedIndex', 0);
        
        deleteCookie('wp_filter_region');

        pafForm.submit();
        lafForm.submit();

    });

    pafForm.submit(function(e){
        e.preventDefault(); 
        
        console.log("form submitted");
    
        if(null != pafForm.find("#provider_region").val() && pafForm.find("#provider_region").val().length !== 0) {
            var region = pafForm.find("#provider_region").val();
        }
        if(null != pafForm.find("#provider_title").val() && pafForm.find("#provider_title").val().length !== 0) {
            var title = pafForm.find("#provider_title").val();
        }
        if(pafForm.find("#providers-ids").val().length !== 0) {
            var providers = pafForm.find("#providers-ids").val();
        }

        if (region){
            createCookie('wp_filter_region', region);
            console.log('cookie set: ' + region);
        } else {
            deleteCookie('wp_filter_region');
            var url = window.location.toString();
            var clean_url = removeURLParameter(url, '_filter_region');
            window.history.replaceState({}, document.title, clean_url);
            //console.log('session emptied' + getCookie('_filter_region'));
        }

        $("#provider-ajax-filter-message").hide();
        $("#location-ajax-filter-message").hide();    
        //console.log(providers);

    
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            dataType: 'html',
            data: {
                action : "provider_ajax_filter",
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
                $("#provider_title > option").attr("disabled", function() {
                    available_title = $('#provider_ids').data('titles').toString();
                    titleArray = [];
                    titleArray = available_title.split(",");
                    // console.log(titleArray);
                    if( $.inArray( $(this).val(), titleArray ) == -1 ) {
                        return true; //available_title.includes( $(this).val() );
                    } else {
                        return false;
                    }
                });
                $("#provider_region > option").attr("disabled", function() {
                    available_regions = $('#provider_ids').data('regions');
                    regionArray = [];
                    regionArray = available_regions.split(",");
                    // console.log(regionArray); 
                    if( $.inArray( $(this).val(), regionArray ) == -1 ) {
                        return true;
                    } else {
                        return false;
                    }
        
                });
                ajaxHideContent();
            },
        });
        
    });
    lafForm.submit(function(e){
        e.preventDefault(); 
        
        console.log("form submitted");
    
        if(null != lafForm.find("#location_region").val() && lafForm.find("#location_region").val().length !== 0) {
            var region = lafForm.find("#location_region").val();
        }

        if(lafForm.find("#locations").val().length !== 0) {
            var locations = lafForm.find("#locations").val();
        }

        if (region){
            createCookie('wp_filter_region', region);
            console.log('cookie set: ' + region);
        } else {
            deleteCookie('wp_filter_region');
            var url = window.location.toString();
            var clean_url = removeURLParameter(url, '_filter_region');
            window.history.replaceState({}, document.title, clean_url);
        }
        //console.log(providers);

        $("#provider-ajax-filter-message").hide();
        $("#location-ajax-filter-message").hide();

    
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            dataType: 'html',
            data: {
                action : "location_ajax_filter",
                region : region,
                locations : locations
            },
            beforeSend : function ( xhr ) {
                $('.card-list-locations .card').css('transition', '0.3s').css('opacity', '0');
                $('.card-list-locations').fadeIn(300, function() { $(this).before('<div id="ajax-loading-locations" class="ajax-loading">Loading...</div>'); });
            },
            success : function(res) { 
                $('.card-list-locations').html(res);
                location_list = $('#location_ids').data('postids');
                // console.log(location_list);
            },
            complete : function () {
                $('#ajax-loading-locations').remove();
                $('.card-list-locations').css('transition', '1s').css('opacity', '1');
                setTimeout(function() {
                    $('.card-list-locations').removeAttr( "style" );
                }, 1001);
                $("#location_region > option").attr("disabled", function() {
                    available_regions = $('#location_ids').data('regions');
                    regionArray = [];
                    regionArray = available_regions.split(",");
                    // console.log(regionArray); 
                    if( $.inArray( $(this).val(), regionArray ) == -1 ) {
                        return true;
                    } else {
                        return false;
                    }
        
                });
            },
        });
        
    });
});
jQuery(document).ready(function($){
    $("#provider_title > option").attr("disabled", function() {
        titleArray = [];
        if ($('#provider_ids').data('titles')){
            available_title = $('#provider_ids').data('titles').toString();
            titleArray = available_title.split(",");
        }
        // console.log(titleArray);
        if( $.inArray( $(this).val(), titleArray ) == -1 ) {
            return true; //available_title.includes( $(this).val() );
        } else {
            return false;
        }
    });
    $("#provider_region > option").attr("disabled", function() {
        regionArray = [];
        if ($('#provider_ids').data('regions')) {
            available_regions = $('#provider_ids').data('regions');
            regionArray = available_regions.split(","); 
        }
        // console.log(regionArray);
        if( $.inArray( $(this).val(), regionArray ) == -1 ) {
            return true;
        } else {
            return false;
        }
    });
    $("#location_region > option").attr("disabled", function() {
        regionArray = [];
        if ($('#location_ids').data('regions')) {
            available_regions = $('#location_ids').data('regions');
            regionArray = available_regions.split(","); 
        }
        // console.log(regionArray);
        if( $.inArray( $(this).val(), regionArray ) == -1 ) {
            return true;
        } else {
            return false;
        }

    });
    $("#provider_region").change(function(){
        $("#location_region").val( this.value );
        $("#location-ajax-filter form").submit();
    });
    $("#location_region").change(function(){
        $("#provider_region").val( this.value );
        $("#provider-ajax-filter form").submit();
    });
    if(getCookie('wp_filter_region') != null) {
        // console.log(getCookie('wp_filter_region'));
        // set the option to selected that corresponds to what the cookie is set to
        $('#region option[value="' + getCookie('wp_filter_region') + '"]').attr('selected', 'selected');
    }
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
// Cookie Functions
function createCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    var domain = "; domain=" + window.location.hostname;
    document.cookie = name + "=" + value + expires + "; path=/" + domain;
}
function deleteCookie( name ) {
    if( getCookie( name ) ) {
        document.cookie = name + "=" +";expires=Thu, 01 Jan 1970 00:00:01 GMT; path=/; domain=" + window.location.hostname;
    }
}
function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
function removeURLParameter(url, parameter) {
    //prefer to use l.search if you have a location/link object
    var urlparts= url.split('?');   
    if (urlparts.length>=2) {

        var prefix= encodeURIComponent(parameter)+'=';
        var pars= urlparts[1].split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i= pars.length; i-- > 0;) {    
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {  
                pars.splice(i, 1);
            }
        }

        url= urlparts[0]+'?'+pars.join('&');
        return url;
    } else {
        return url;
    }
}