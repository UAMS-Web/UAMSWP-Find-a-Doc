jQuery(function($) {
    var $loader = $('.loadmore');
    var ppp = ("" !== $loader.data('ppp')) ? $loader.data('ppp') : 6; // Set default value
    var postids = ("" !== $('#provider_ids').data('postids')) ? $('#provider_ids').data('postids') : 0; // Post
    
    $('body').on('click', '.loadmore', function() {
        
        postids = $('#provider_ids').data('postids');
        postcount = postids.split(",").length;
        page = ($('.card-list-doctors .card').length / ppp)+1;
        // console.log(page);

        var data = {
            'action': 'load_posts_by_ajax',
            'page': page,
            'postid': postids,
            'security': uamswp_loadmore.security,
            'ppp': ppp
        };

        max_pages = postcount / ppp; // Number of posts per page

        // console.log(max_pages);

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
					$('.loadmore').text( 'Load More' ); // insert new posts
                    $('.card-list-doctors').append(data);
                    postcount = postids.split(",").length;
                    page = $('.card-list-doctors .card').length / ppp;
                    max_pages = postcount / ppp;
                    // console.log(page + ' ' + max_pages);
					if ( page >= max_pages ) {
                        $('#providers .more').hide(); // if last page, remove the button
                        $('.loadmore').hide();
                    }
                    page++;
					// you can also fire the "post-load" event here if you use a plugin that requires it
					// $( document.body ).trigger( 'post-load' );
				} else {
                    $('#providers .more').hide();
                    $('.loadmore').hide();
				}
            },
            error : function (jqXHR, textStatus, errorThrown) {
				$loader.html($.parseJSON(jqXHR.responseText) + ' :: ' + textStatus + ' :: ' + errorThrown);
				console.log(jqXHR);
            },

        });
    });

    var paf = $("#provider-ajax-filter"); 
    var pafForm = paf.find("form");

    pafForm.find("#clear").on('click', function(e){
        e.preventDefault(); 
     
        console.log("form cleared");

        pafForm.find("#region").prop('selectedIndex', 0);
        pafForm.find("#title").prop('selectedIndex', 0);
        
        deleteCookie('providerRegion');
        deleteCookie('providerTitle');

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
            createCookie('providerRegion', region, 1);
            // console.log('cookie set: ' + region);
        } else {
           deleteCookie('providerRegion');
        //    console.log('cookie emptied' + getCookie('providerRegion'));
        }
        if(title){
            createCookie('providerTitle', title, 1);
            // console.log('cookie set: ' + title);
        } else {
           deleteCookie('providerTitle');
        //    console.log('cookie emptied' + getCookie('providerTitle'));
        }

        //console.log(providers);

    
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