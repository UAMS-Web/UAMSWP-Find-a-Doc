var page = 2;
var $loader = $('.loadmore');
var postids = $loader.data('postids');
var ppp = ("" !== $loader.data('ppp')) ? $loader.data('ppp') : 2; // Set default value
// console.log(postids);
jQuery(function($) {
    $('body').on('click', '.loadmore', function() {
        

        var data = {
            'action': 'load_posts_by_ajax',
            'page': page,
            'security': uamswp_loadmore.security,
            'postid': postids,
            'ppp': ppp
        };

        // console.log(page);

        // console.log(data);

        docs = postids.split(",");

        max_pages = docs.length / ppp; // Number of posts per page

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
				if( data ) { 
					$('.loadmore').text( 'More posts' ); // insert new posts
					$('.card-list-doctors').append(data);
                    
					if ( page >= max_pages ) 
                        // button.remove(); // if last page, remove the button
                        $('.loadmore').hide();
                    page++;
					// you can also fire the "post-load" event here if you use a plugin that requires it
					// $( document.body ).trigger( 'post-load' );
				} else {
                    $('.loadmore').hide();
					// button.remove(); // if no data, remove the button as well
				}
            },
            error : function (jqXHR, textStatus, errorThrown) {
				$loader.html($.parseJSON(jqXHR.responseText) + ' :: ' + textStatus + ' :: ' + errorThrown);
				console.log(jqXHR);
			},

        });
    });
});