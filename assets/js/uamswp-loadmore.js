jQuery(function($) {
    var page = 2; // Default to start on page 2
    var $loader = $('.loadmore');
    var ppp = ("" !== $loader.data('ppp')) ? $loader.data('ppp') : 6; // Set default value
    var postcount = ("" !== $loader.data('postcount')) ? $loader.data('postcount') : 0; // Break 
    var type = ("" !== $loader.data('type')) ? $loader.data('type') : 'post';
    var postids = ("" !== $loader.data('postids')) ? $loader.data('postids') : 0; // Post
    var tax = ("" !== $loader.data('tax')) ? $loader.data('tax') : 0; // Taxonomy
    var slug = ("" !== $loader.data('slug')) ? $loader.data('slug') : 0; // Taxonomy

    $('body').on('click', '.loadmore', function() {
        

        var data = {
            'action': 'load_posts_by_ajax',
            'page': page,
            'security': uamswp_loadmore.security,
            'postid': postids,
            'ppp': ppp,
            'postcount': postcount,
            'posttype': type,
            'tax': tax,
            'slug': slug
        };

        // console.log(page);

        // console.log(data);

        // docs = postids.split(",");

        max_pages = postcount / ppp; // Number of posts per page

        // console.log(max_pages);
 
        // $.post(blog.ajaxurl, data, function(response) {
        //     if(response != '') {
        //         $('.card-list-doctors').append(response);
        //         if ( page >= max_pages )
        //             $('.loadmore').hide();
        //         page++;
        //     } else {
        //         $('.loadmore').hide();
        //     }
        // });

        $.ajax({
            url : uamswp_loadmore.ajaxurl,
            data : data,
            type : 'POST',
            datatype : 'html',
            beforeSend : function ( xhr ) {
				$('.loadmore').text('Loading...'); // change the button text, you can also add a preloader image
			},
			success : function( data ){
                // console.log(data);
				if( data ) { 
					$('.loadmore').text( 'Load More' ); // insert new posts
                    $('.card-list-doctors').append(data);
					if ( page >= max_pages ) {
                        // button.remove(); // if last page, remove the button
                        $('#doctors .more').hide();
                        $('.loadmore').hide();
                    }
                    page++;
					// you can also fire the "post-load" event here if you use a plugin that requires it
					// $( document.body ).trigger( 'post-load' );
				} else {
                    $('#doctors .more').hide();
                    $('.loadmore').hide();
					// button.remove(); // if no data, remove the button as well
				}
            },
            error : function (jqXHR, textStatus, errorThrown) {
				$loader.html($.parseJSON(jqXHR.responseText) + ' :: ' + textStatus + ' :: ' + errorThrown);
				console.log(jqXHR);
            },
            // error: function(errorThrown){
            //     alert(errorThrown);
            //     alert("There is an error with AJAX!");
            // }

        });
    });
});