jQuery(function($) {
    //var page = 2; // Default to start on page 2
    var $loader = $('.loadmore');
    var ppp = ("" !== $loader.data('ppp')) ? $loader.data('ppp') : 6; // Set default value
    // var postcount = ("" !== $loader.data('postcount')) ? $loader.data('postcount') : 0; // Break 
    // var type = ("" !== $loader.data('type')) ? $loader.data('type') : 'post';
    var postids = ("" !== $('#provider_ids').data('postids')) ? $('#provider_ids').data('postids') : 0; // Post
    // var tax = ("" !== $loader.data('tax')) ? $loader.data('tax') : 0; // Taxonomy
    // var slug = ("" !== $loader.data('slug')) ? $loader.data('slug') : 0; // Taxonomy
    
    //postids = $('#provider_ids').data('postids');

    $('body').on('click', '.loadmore', function() {
        
        postids = $('#provider_ids').data('postids');
        postcount = postids.split(",").length;
        page = ($('.card-list-doctors .card').length / ppp)+1;
        console.log(ppp);

        var data = {
            'action': 'load_posts_by_ajax',
            'page': page,
            'postid': postids,
            'security': uamswp_loadmore.security,
            'ppp': ppp
        };

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
                    postcount = postids.split(",").length;
                    page = $('.card-list-doctors .card').length / ppp;
                    max_pages = postcount / ppp;
                    // console.log(page + ' ' + max_pages);
					if ( page >= max_pages ) {
                        // button.remove(); // if last page, remove the button
                        $('#providers .more').hide();
                        $('.loadmore').hide();
                    }
                    page++;
					// you can also fire the "post-load" event here if you use a plugin that requires it
					// $( document.body ).trigger( 'post-load' );
				} else {
                    $('#providers .more').hide();
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

    var paf = $("#provider-ajax-filter"); 
    var pafForm = paf.find("form");

    pafForm.find("#clear").on('click', function(e){
        e.preventDefault(); 
     
        console.log("form cleared");

        pafForm.find("#region").prop('selectedIndex', 0);
        pafForm.find("#title").prop('selectedIndex', 0);

        pafForm.submit();

    });

    pafForm.submit(function(e){
        e.preventDefault(); 
     
        console.log("form submitted");
    
        if(pafForm.find("#region").val().length !== 0) {
            var region = pafForm.find("#region").val();
        }
        if(pafForm.find("#title").val().length !== 0) {
            var title = pafForm.find("#title").val();
        }
        if(pafForm.find("#providers").val().length !== 0) {
            var providers = pafForm.find("#providers").val();
        }

        if(pafForm.find("#ppp").val().length !== 0) {
            var ppp = pafForm.find("#ppp").val();
        }

        if (region){
            createCookie('providerRegion', region);
            console.log('cookie set: ' + region);
        } else {
           createCookie('providerRegion', '');
        }
        if(title){
            createCookie('providerTitle', title);
            console.log('cookie set: ' + title);
        } else {
           createCookie('providerTitle', '');
        }

        console.log(providers);

    
        $.ajax({
            type: 'POST',
            url: '/wp-admin/admin-ajax.php',
            dataType: 'html',
            data: {
                action : "provider_ajax_filter",
                region : region,
                title : title,
                providers : providers,
                ppp : ppp
            },
            beforeSend : function ( xhr ) {
                $('#providers .more').hide();
                $('.card-list-doctors').text('Loading...'); 
            },
            success : function(res) { 
              $('.card-list-doctors').html(res);
              provider_list = $('#provider_ids').data('postids');
              var provider_array = [];
              if (provider_list) {
                provider_array = provider_list.split(",");
                max_pages = provider_array.length / ppp;
              } else {
                max_pages = 0;
              }
              if ( provider_array.length > ppp && 1 < max_pages ) {
                $('#providers .more').show();
                $('.loadmore').show();
              }
            },
          });
     
    });
});
function createCookie(name, value, days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + value + expires + "; path=/";
}