/* global gtw_enhanced_select_params */

jQuery( function ( $ ) {
	'use strict' ;

	function gtw_get_enhanced_select_format_string( ) {
		return {
			'language' : {
				errorLoading : function () {
					return gtw_enhanced_select_params.i18n_searching ;
				} ,
				inputTooLong : function ( args ) {
					var overChars = args.input.length - args.maximum ;

					if ( 1 === overChars ) {
						return gtw_enhanced_select_params.i18n_input_too_long_1 ;
					}

					return gtw_enhanced_select_params.i18n_input_too_long_n.replace( '%qty%' , overChars ) ;
				} ,
				inputTooShort : function ( args ) {
					var remainingChars = args.minimum - args.input.length ;

					if ( 1 === remainingChars ) {
						return gtw_enhanced_select_params.i18n_input_too_short_1 ;
					}

					return gtw_enhanced_select_params.i18n_input_too_short_n.replace( '%qty%' , remainingChars ) ;
				} ,
				loadingMore : function () {
					return gtw_enhanced_select_params.i18n_load_more ;
				} ,
				maximumSelected : function ( args ) {
					if ( args.maximum === 1 ) {
						return gtw_enhanced_select_params.i18n_selection_too_long_1 ;
					}

					return gtw_enhanced_select_params.i18n_selection_too_long_n.replace( '%qty%' , args.maximum ) ;
				} ,
				noResults : function () {
					return gtw_enhanced_select_params.i18n_no_matches ;
				} ,
				searching : function () {
					return gtw_enhanced_select_params.i18n_searching ;
				}
			}
		} ;
	}

	try {
		$( document.body ).on( 'gtw-enhanced-init' , function () {
			if ( $( 'select.gtw_select2' ).length ) {
				//Select2 with customization
				$( 'select.gtw_select2' ).each( function () {
					var select2_args = {
						allowClear : $( this ).data( 'allow_clear' ) ? true : false ,
						placeholder : $( this ).data( 'placeholder' ) ,
						minimumResultsForSearch : 10 ,
					} ;

					select2_args = $.extend( select2_args , gtw_get_enhanced_select_format_string() ) ;

					$( this ).select2( select2_args ) ;
				} ) ;
			}
			if ( $( 'select.gtw_select2_search' ).length ) {
				//Multiple select with ajax search
				$( 'select.gtw_select2_search' ).each( function () {
					var select2_args = {
						allowClear : $( this ).data( 'allow_clear' ) ? true : false ,
						placeholder : $( this ).data( 'placeholder' ) ,
						minimumInputLength : $( this ).data( 'minimum_input_length' ) ? $( this ).data( 'minimum_input_length' ) : 3 ,
						escapeMarkup : function ( m ) {
							return m ;
						} ,
						ajax : {
							url : gtw_enhanced_select_params.ajaxurl ,
							dataType : 'json' ,
							delay : 250 ,
							data : function ( params ) {
								return {
									term : params.term ,
									action : $( this ).data( 'action' ) ? $( this ).data( 'action' ) : 'gtw_json_search_customers' ,
									exclude_global_variable : $( this ).data( 'exclude-global-variable' ) ? $( this ).data( 'exclude-global-variable' ) : 'no' ,
									gtw_security : $( this ).data( 'nonce' ) ? $( this ).data( 'nonce' ) : gtw_enhanced_select_params.search_nonce ,
								} ;
							} ,
							processResults : function ( data ) {
								var terms = [ ] ;
								if ( data ) {
									$.each( data , function ( id , term ) {
										terms.push( {
											id : id ,
											text : term
										} ) ;
									} ) ;
								}
								return {
									results : terms
								} ;
							} ,
							cache : true
						}
					} ;

					select2_args = $.extend( select2_args , gtw_get_enhanced_select_format_string() ) ;

					$( this ).select2( select2_args ) ;
				} ) ;
			}

		} ) ;

		$( document.body ).trigger( 'gtw-enhanced-init' ) ;
	} catch ( err ) {
		window.console.log( err ) ;
	}

} ) ;
