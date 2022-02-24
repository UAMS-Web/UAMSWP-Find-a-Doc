// -- Used in title only & region filter -- //
// -- Moved inside each file to avoid errors -- //

// jQuery(function($) {
//     $(document).ready(ajaxHideContent);
//     $(window).resize(ajaxHideContent);
//     function ajaxHideContent(){
//         // Find the height of the relevant elements
//         var $providerLoadMore = $('#providers .ajax-filter-load-more').outerHeight();
//         var $providerCardHeight = $('#providers .card:first-child').outerHeight() + 15;
//         var $providerCardListHeight = $('#providers .card-list').outerHeight() - 15;
//         // console.log($providerCardListHeight);
//         var $providerHideContentHeight = $providerCardHeight + ($providerLoadMore * 0.9);

//         // Set the max-height of the card list container based on that math
//         $('#providers.hideContent .card-list-container').css("max-height", $providerHideContentHeight);
//         // Add / remove overflow class based on math
//         if ($providerCardListHeight > $providerCardHeight) {
//             $("#providers").addClass("overflow");
//         } else {
//             $("#providers").removeClass("overflow");
//         }
//     }
// });
// jQuery(document).ready(function($){
//     // Make the Load More button do things
//     $(".ajax-filter-load-more button").on("click", function() {
//         var $providerCardListHeight = $('#providers .card-list').outerHeight() - 15;
//         // Transition the container
//         $("#providers .card-list-container").css("max-height", $providerCardListHeight);
//         $("#providers").addClass("expanded");
//         //$("#providers .card-list-container").removeAttr( "style" );
//         // Set max-height to none and remove transition after transition
//         setTimeout(function() {
//             // $("#providers.expanded .card-list-container").css("max-height", "none").css("transition", "none");
//             $("#providers.expanded .card-list-container").css("max-height", "none");
//         }, 1001);
//     });
//     if( $('#providers').length ) {
//         // Set the load more background colors based on the section's background color
//         var $ajaxSectionBGColor = $( "#providers" ).css( "background-color" ).replace("rgb(", "").replace(")", '');
//         $( "#providers .ajax-filter-load-more" ).css( "background-image", "linear-gradient(180deg, rgba(" + $ajaxSectionBGColor + ",0) 0%, rgba(" + $ajaxSectionBGColor + ",1) 75%)" );
//         $( "#providers .ajax-filter-load-more .btn" ).css( "box-shadow", "0 0 1rem 1rem rgb(" + $ajaxSectionBGColor + ",0.5)" );
//     }
// });