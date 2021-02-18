// FWP scroll for pager
(function($) {
    $(document).on('facetwp-loaded', function() {
        if (FWP.loaded) {
            $('html, body').animate({
                scrollTop: $('.facetwp-template').offset().top
            }, 500);
        }
    });
})(jQuery);