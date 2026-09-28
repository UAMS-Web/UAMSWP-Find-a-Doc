jQuery(document).ready(function($) {

    let npi = '';
    let post_id = '';
    let total_records = '';
    if (null != $('#comment-list').data('npi') && $('#comment-list').data('npi').length !== 0){
        npi = $('#comment-list').data('npi');
    }
    if (null != $('#comment-list').data('postid') && $('#comment-list').data('postid').length !== 0){
        post_id = $('#comment-list').data('postid');
    }
    if (null != $('#comment-list').data('commentcount') && $('#comment-list').data('commentcount').length !== 0){
        total_records = $('#comment-list').data('commentcount');
    }
    // var total_records = ;
    if (npi.length == 0 || post_id.length == 0 || total_records.length == 0) {
        return false;
    }
    var nonce = (typeof uamswp_ajax_scripts !== 'undefined' && uamswp_ajax_scripts.security) ? uamswp_ajax_scripts.security : '';
    var ajax_url = (typeof uamswp_ajax_scripts !== 'undefined' && uamswp_ajax_scripts.ajaxurl) ? uamswp_ajax_scripts.ajaxurl : '/wp-admin/admin-ajax.php';

    var currentPage = 1;
    var perPage = 10; // Adjust as needed
    var total_pages = Math.ceil(total_records / perPage);

    function loadData(page) {
      showLoadingSpinner();

      $.ajax({
        url: ajax_url,
        type: 'POST',
        dataType: 'html',
        data: {
            action : "pg_ajax_api_action",
            nonce : nonce,
            post_id : post_id,
            page : page,
        },
        success: function(response) {
            // console.log(response);
            renderData(response);
            updatePaginationControls(total_pages, page);
        },
        error: function(error) {
            console.error("Error fetching data:", error);
        },
        complete: function() {
            hideLoadingSpinner(); // Hide the loading spinner
        }
      });
    }

    function showLoadingSpinner() {
        $('.loading-spinner').show();
    }

    function hideLoadingSpinner() {
        $('.loading-spinner').hide();
    }

    function renderData(data) {
        const cardList = $('#comment-list');
        cardList.empty();
        cardList.append(data);
    }

    function updatePaginationControls(totalPages, currentPage) {
        const paginationControls = $('#pagination-controls');
        paginationControls.empty();

        if(currentPage == 1){
            // paginationControls.append("<li class='page-item disabled previous'><button class='page-link'><</button></li>");
            button = $('<button>');
            button.text('<');
            button.addClass('disabled');
            paginationControls.append(button);
        }else{
            // paginationControls.append("<li class='page-item'><button onclick='loadData("+(currentPage-1)+")' class='page-link'><</button></li>");
            button = $('<button>');
            button.text('<');
            // button.addClass('disabled');
            button.click(function() {
              loadData(currentPage-1);
            });
            paginationControls.append(button);
        }

        var i=0;
        for(i=0; i <= 2; i++){
            if(currentPage == (currentPage+i)){
                // paginationControls.append("<li class='page-item disabled'><button class='page-link'>"+(currentPage+i)+"</button></li>");
                button = $('<button>');
                button.text(currentPage+i);
                // if (i === currentPage) {
                button.addClass('active');
                // }
                button.click(function() {
                  loadData(currentPage+i);
                });
                paginationControls.append(button);
            }else{
                if((currentPage+i)<=totalPages){
                    button = $('<button>');
                    button.text(currentPage+i);
                    button.click(function() {
                      loadData(currentPage+i);
                    });
                    // paginationControls.append("<li class='page-item'><button onclick='loadData("+(currentPage+i)+")' class='page-link'>"+(currentPage+i)+"</button></li>");
                    paginationControls.append(button);
                }
            }
        }

        if(currentPage == totalPages){
            // paginationControls.append("<li class='page-item disabled'><button class='page-link'>></button></li>");
            button = $('<button>');
            button.text('>');
            button.addClass('disabled');
            paginationControls.append(button);
        }else{
            // paginationControls.append("<li class='page-item next'><button onclick='loadData("+(currentPage+1)+")' class='page-link'>></button></li>");
            button = $('<button>');
            button.text('>');
            // button.addClass('disabled');
            button.click(function() {
              loadData(currentPage+1);
            });
            paginationControls.append(button);
        }
    }

    // Initial load
    loadData(currentPage);
});