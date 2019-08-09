
	jQuery( function ( $ ){
		$( '#wpbody' ).on( 'change', '.pubmed-update input', function () {

		// extend the acf.ajax object
		// you should probably rename this var
		var $group = $( this ).closest( '.rwmb-group-clone' ),
			pmid = $group.find( '.rwmb-text-wrapper input' ),
			$pminfo = $group.find( '.rwmb-textarea-wrapper .rwmb-input textarea' );
			

			// clear existing values from the fields we will update
			// clear input field
			$( $pminfo ).val('');

			// get the input value
			var $value = pmid.val();

			// if there is no value, exit
			if (!$value) {
				return;
			}

			//console.log('started ' + $value);

			// now we can do our ajax request

			// I assume this tests to see if there is already a request
			// for this and cancels it if there is
			if( this.request) {
				this.request.abort();
			}

			// make ajax request
			this.request = $.ajax({
				url:		ajaxurl,
				data:		{action: "load_content_from_pubmed", pmid: $value},
				type:		'post',
				dataType:	'json',
				async: true,
				success: function(json){

					if (!json) {
						return;
					}

					console.log('ajax success: ' + json['id'] );

					//console.log(json);

					//console.log($pminfo);

					//console.log('pubmed:' + $pminfo.val());

					// put the values into the fields
					if (json['full']) {
						$pminfo.val(json['full']);
					}
				},
				error: function(jqXHR, textStatus, error) {
					alert (jqXHR+' : '+textStatus+' : '+error);
				}
			});
			//},
		} );

		$( '.pubmed-update input' ).trigger( 'change' );

	});
