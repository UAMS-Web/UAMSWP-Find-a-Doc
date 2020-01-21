(function($) {
    $(function() {
        FWP.hooks.addAction('facetwp/refresh/alpha', function($this, facet_name) {
            FWP.facets[facet_name] = $this.find('.facetwp-alpha.selected').attr('data-id') || '';
        });
    });

    $(document).on('click', '.facetwp-alpha.available', function() {
        $parent = $(this).closest('.facetwp-facet');
        $parent.find('.facetwp-alpha').removeClass('selected');

        $parent.find('.facetwp-alpha').not('.disabled').attr('aria-selected', 'false');
        var facet_name = $parent.attr('data-name');
        $(this).addClass('selected');
        $(this).attr('aria-selected', 'true');

        if ('' == $(this).attr('data-id')) {
            $parent.children().first().addClass('selected');
            console.log( $parent.children().first() );
        }

        if ('' !== $(this).attr('data-id')) {
            FWP.frozen_facets[facet_name] = 'soft';
        }
        // FWP.refresh();
    });

    $(document).on('keydown', '.facetwp-alpha.available', function(e) {
        var keyCode = e.originalEvent.keyCode;
        if ( 32 == keyCode || 13 == keyCode ) {
            last_checked = $(this).attr('data-value');
            e.preventDefault();
            $(this).click();
        }
    });
})(jQuery);
