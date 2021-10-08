
jQuery( function ( $ ) {
	'use strict' ;
	try {

		$( document.body ).on( 'gtw-enhanced-lightcase' , function ( ) {

			var light_cases = $( '.gtw-popup-gift-wrapper' ) ;
			if ( !light_cases.length ) {
				return ;
			}

			light_cases.each( function ( ) {
				$( this ).lightcase( {
					href : $( this ).data( 'popup' ) ,
					onFinish : {
						foo : function ( ) {
							lightcase.resize( ) ;
						}
					} ,
				} ) ;
			} ) ;
		} ) ;
		// Initialize lightcase when cart updated.
		$( document.body ).on( 'updated_wc_div' , function ( ) {
			$( document.body ).trigger( 'gtw-enhanced-lightcase' ) ;
		} ) ;
		$( document.body ).trigger( 'gtw-enhanced-lightcase' ) ;
	} catch ( err ) {
		window.console.log( err ) ;
	}

} ) ;
