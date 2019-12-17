jQuery(document).ready(function($){
    $( ".filter-toggle, .filter-col .close" ).click(function() {
        $( ".filter-col" ).toggleClass( "show" );
    });
    $( "#filter-apply" ).click(function() {
        $( ".filter-col" ).toggleClass( "show" );
    });
});