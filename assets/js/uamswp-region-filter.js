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

    function normalizeRegionValue(val) {
        if (val === null || val === undefined) return "";
        var s = String(val).trim();
        if (!s) return "";
        if (s.toLowerCase() === "any") return "";
        return s;
    }

    /**
     * Parse a CSV data attribute into an array.
     * - Returns null when attribute is missing/undefined
     * - Returns [] when attribute exists but is empty
     */
    function parseCsvData(val) {
        if (val === null || val === undefined) return null;
        var s = String(val).trim();
        if (!s) return [];
        // data attributes in this plugin often end with a trailing comma
        return s.split(",").map(function(x) { return String(x).trim(); }).filter(Boolean);
    }

    function captureBaselineDisabled($select) {
        if (!$select || !$select.length) return;
        $select.find("option").each(function() {
            $(this).data("baselineDisabled", $(this).prop("disabled"));
        });
    }

    function restoreBaselineDisabled($select) {
        if (!$select || !$select.length) return;
        $select.find("option").each(function() {
            var baseline = $(this).data("baselineDisabled");
            // If baseline was never captured, do nothing.
            if (baseline === undefined) return;
            $(this).prop("disabled", baseline);
        });
    }

    /**
     * Disable options based on availability list, while respecting baseline disabled options.
     * - If availability list is missing/empty, restore baseline disabled state.
     * - Always keep "" and "any" enabled.
     */
    function applyAvailabilityDisabled($select, availableList) {
        if (!$select || !$select.length) return;
        // availableList meanings:
        // - null: no availability info, restore baseline disabled state
        // - []: explicitly none available, disable all non-any options
        // - [..]: disable options not in list (plus any baseline disabled)
        var hasList = Array.isArray(availableList) && availableList.length > 0;
        var explicitNone = Array.isArray(availableList) && availableList.length === 0;
        $select.find("option").each(function() {
            var $opt = $(this);
            var val = $opt.val();
            var valLower = (val === null || val === undefined) ? "" : String(val).toLowerCase();

            if (val === "" || valLower === "any") {
                $opt.prop("disabled", false);
                return;
            }

            var baseline = $opt.data("baselineDisabled");
            baseline = (baseline === undefined) ? false : !!baseline;

            if (availableList === null) {
                $opt.prop("disabled", baseline);
                return;
            }
            if (explicitNone) {
                $opt.prop("disabled", true);
                return;
            }

            $opt.prop("disabled", baseline || $.inArray(val, availableList) === -1);
        });
    }

    // Capture baseline disabled states from PHP-rendered markup
    captureBaselineDisabled(pafForm.find("#provider_region"));
    captureBaselineDisabled(pafForm.find("#provider_title"));
    captureBaselineDisabled(lafForm.find("#location_region"));

    pafForm.find('select').on('change', function(){
        pafForm.submit();
    });

    lafForm.find('select').on('change', function(){
        lafForm.submit();
    });

    pafForm.find("#provider_clear").on('click', function(e){
        e.preventDefault(); 
        
        console.log("form cleared");

        // Restore baseline option disables (regions with no providers stay disabled)
        restoreBaselineDisabled(pafForm.find("#provider_region"));
        restoreBaselineDisabled(pafForm.find("#provider_title"));
        restoreBaselineDisabled(lafForm.find("#location_region"));

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

        // Restore baseline option disables (regions with no providers stay disabled)
        restoreBaselineDisabled(pafForm.find("#provider_region"));
        restoreBaselineDisabled(pafForm.find("#provider_title"));
        restoreBaselineDisabled(lafForm.find("#location_region"));

        pafForm.find("#provider_region").prop('selectedIndex', 0);

        lafForm.find("#location_region").prop('selectedIndex', 0);
        
        deleteCookie('wp_filter_region');

        pafForm.submit();
        lafForm.submit();

    });

    pafForm.submit(function(e){
        e.preventDefault(); 
        
        console.log("form submitted");
    
        var region = normalizeRegionValue(pafForm.find("#provider_region").val());
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
                // Always prefer explicit data-* attributes (even if empty) over baseline.
                var $providerData = $('#provider_ids');
                var titleArray = ($providerData.length) ? parseCsvData($providerData.attr('data-titles')) : null;
                var regionArray = ($providerData.length) ? parseCsvData($providerData.attr('data-regions')) : null;
                applyAvailabilityDisabled($("#provider_title"), titleArray);
                applyAvailabilityDisabled($("#provider_region"), regionArray);
                ajaxHideContent();
            },
        });
        
    });
    lafForm.submit(function(e){
        e.preventDefault(); 
        
        console.log("form submitted");
    
        var region = normalizeRegionValue(lafForm.find("#location_region").val());

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
                var regionArray = parseCsvData($('#location_ids').data('regions'));
                applyAvailabilityDisabled($("#location_region"), regionArray);
            },
        });
        
    });
});
jQuery(document).ready(function($){
    // Initial option disabling is handled by PHP markup (baseline) + AJAX completes.
    // We intentionally do not override option disabled states here to avoid enabling
    // regions that have no providers when AJAX data isn't present.
    $("#provider_region").change(function(){
        $("#location_region").val( this.value );
        $("#location-ajax-filter form").submit();
    });
    $("#location_region").change(function(){
        $("#provider_region").val( this.value );
        $("#provider-ajax-filter form").submit();
    });
    // Restore region from cookie (treat "any" as unset)
    var cookieRegion = (function() {
        var v = getCookie('wp_filter_region');
        if (v === null || v === undefined) return "";
        v = String(v).trim();
        if (!v) return "";
        if (v.toLowerCase() === "any") return "";
        return v;
    })();
    if (cookieRegion) {
        $("#provider_region, #location_region").val(cookieRegion);
    } else {
        // If the cookie is empty/"any", clear it to avoid sticky bad state
        deleteCookie('wp_filter_region');
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