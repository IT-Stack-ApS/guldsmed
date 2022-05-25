jQuery(function($){ // use jQuery code inside this to avoid "$ is not defined" error
	$('.loadmore_button').click(function(){

		var button = $(this),
		    data = {
			'action': 'loadmore',
			'query': loadmore_params.posts,
			'page' : loadmore_params.current_page
		};

		$.ajax({ // you can also use $.post here
			url : loadmore_params.ajaxurl, // AJAX handler
			data : data,
			type : 'POST',
			beforeSend : function ( xhr ) {
				button.text('Henter...'); // change the button text, you can also add a preloader image
			},
			success : function( data ){
				if( data ) {
					button.text( 'Se flere produkter' ).prev().before(data); // insert new posts
					loadmore_params.current_page++;


					/* Progress bar */
					var post_count = Number(loadmore_params.post_count);
					var current_page = Number(loadmore_params.current_page );
					var post_found = loadmore_params.post_found;
					var post_count_total = post_count * current_page;

					$('#progress-bar').val( post_count_total );
					 					
					if ( post_count_total > post_found ) {
						$('.woocommerce-result-count ').replaceWith('<p class="woocommerce-result-count">Viser ' + post_found  + ' af ' + post_found + ' resultater</p>' );
					}
					else {
						$('.woocommerce-result-count ').replaceWith('<p class="woocommerce-result-count">Viser ' + post_count_total + ' af ' + post_found + ' resultater</p>' );
					}
					/* End progress bar */
					

					if ( loadmore_params.current_page == loadmore_params.max_page )
						button.remove(); // if last page, remove the button

					// $( document.body ).trigger( 'post-load' );
				} else {
					button.remove(); // if no data, remove the button as well
				}
			}
		});
	});
});