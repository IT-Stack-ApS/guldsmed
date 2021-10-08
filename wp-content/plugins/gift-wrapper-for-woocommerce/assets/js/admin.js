/* global gtw_admin_params, ajaxurl */

jQuery( function ( $ ) {
	'use strict' ;

	var FGF_Admin = {
		init : function ( ) {
			this.trigger_on_page_load() ;

			// Rule Settings.
			$( document ).on( 'click' , '.gtw_add_new_rule' , this.add_new_rule ) ;
			$( document ).on( 'click' , '.gtw-remove-rule' , this.remove_rule ) ;
			$( document ).on( 'click' , '.gtw-select-image' , this.select_image ) ;

			// Product settings.
			$( document ).on( 'click' , '.gtw-add-product-new-rule' , this.add_product_new_rule ) ;
			$( document ).on( 'click' , '.gtw-product-remove-rule' , this.remove_product_rule ) ;
			$( document ).on( 'change' , '#_gtw_design_type' , this.toggle_product_design_type ) ;

			//Settings Tab
			$( document ).on( 'change' , '#gtw_settings_gift_wrapper_design_type' , this.toggle_gift_wrapper_design_type ) ;
			$( document ).on( 'change' , '.gtw-enabled-product-gift-wrapper' , this.toggle_product_gift_wrapper ) ;
			$( document ).on( 'change' , '.gtw_enabled_order_gift_wrapper' , this.toggle_order_gift_wrapper ) ;
			$( document ).on( 'change' , '#gtw_settings_gift_wrapping_product_restriction' , this.toggle_product_restriction ) ;
			$( document ).on( 'click' , '#gtw_settings_create_order_gift_wrapper_product' , this.create_order_gift_wrapper_product ) ;
			$( document ).on( 'change' , '#gtw_settings_order_gift_wrapper_product_type' , this.toggle_order_gift_wrapper_product_type ) ;
			$( document ).on( 'change' , '#gtw_settings_show_order_gift_wrapper_remove_notice' , this.toggle_order_gift_wrapper_remove_notice ) ;

			// Prevent settings save in functionality.
			$( 'form#gtw_settings_form' ).on( 'submit' , this.prevent_save_settings ) ;

		} , trigger_on_page_load : function ( ) {
			// Product settings.
			this.product_design_type( '#_gtw_design_type' ) ;

			//Settings Tab
			this.gift_wrapper_design_type( '#gtw_settings_gift_wrapper_design_type' ) ;
			this.product_gift_wrapper( '.gtw-enabled-product-gift-wrapper' ) ;
			this.order_gift_wrapper( '.gtw_enabled_order_gift_wrapper' ) ;
			this.product_restriction( '#gtw_settings_gift_wrapping_product_restriction' ) ;

			this.drag_rules() ;
			this.drag_fields() ;
			this.drag_product_rules() ;
		} , drag_rules : function () {
			var listtable = $( 'table.gtw-gift-wrapper-designs-table' ).find( 'tbody' ) ;

			listtable.sortable( {
				items : 'tr' ,
				handle : '.gtw-drag-rules-wrapper' ,
				axis : 'y' ,
				containment : listtable ,
				update : function ( event , ui ) {
					var sort_order = [ ] ;

					listtable.find( '.gtw-drag-rule-id' ).each( function ( e ) {
						sort_order.push( $( this ).val( ) ) ;
					} ) ;

					$.post( ajaxurl , {
						action : 'gtw_drag_rules' ,
						sort_order : sort_order ,
						gtw_security : gtw_admin_params.rules_nonce
					} ) ;
				}
			} ) ;
		} , drag_fields : function () {
			var listtable = $( 'table.woocommerce_page_gtw_settings #the-list' ).closest( 'table' ) ;

			listtable.sortable( {
				items : 'tr' ,
				handle : '.gtw-post-sort-handle' ,
				axis : 'y' ,
				containment : listtable ,
				update : function ( event , ui ) {
					var sort_order = [ ] ;

					listtable.find( '.gtw-custom-field-sortable' ).each( function ( e ) {
						sort_order.push( $( this ).data( 'id' ) ) ;
					} ) ;

					$.post( ajaxurl , {
						action : 'gtw_drag_fields' ,
						sort_order : sort_order ,
						gtw_security : gtw_admin_params.rules_nonce
					} ) ;
				}
			} ) ;
		} , drag_product_rules : function () {
			var listtable = $( 'table.gtw-product-gift-wrapper-designs-table' ).find( 'tbody' ) ;

			listtable.sortable( {
				items : 'tr' ,
				handle : '.gtw-product-drag-rules-wrapper' ,
				axis : 'y' ,
				containment : listtable ,
			} ) ;
		} , toggle_product_design_type : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ;

			FGF_Admin.product_design_type( $this ) ;
		} , toggle_gift_wrapper_design_type : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ;

			FGF_Admin.gift_wrapper_design_type( $this ) ;
		} , toggle_product_restriction : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ;

			FGF_Admin.product_restriction( $this ) ;
		} , toggle_product_gift_wrapper : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ;

			FGF_Admin.product_gift_wrapper( $this ) ;
		} , toggle_order_gift_wrapper : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ;

			FGF_Admin.order_gift_wrapper( $this ) ;
		} , toggle_order_gift_wrapper_product_type : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ;

			FGF_Admin.order_gift_wrapper_product_type( $this ) ;
		} , toggle_order_gift_wrapper_remove_notice : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ;

			FGF_Admin.order_gift_wrapper_remove_notice( $this ) ;
		} , prevent_save_settings : function ( event ) {
			var error_message = false ;
			var cart_order_gift_wrapper = $( '#gtw_settings_enable_cart_order_gift_wrapper' ) ,
					checkout_order_gift_wrapper = $( "#gtw_settings_enable_checkout_order_gift_wrapper" ) ;

			if ( cart_order_gift_wrapper.is( ':checked' ) || checkout_order_gift_wrapper.is( ':checked' ) ) {
				if ( '1' == $( '#gtw_settings_order_gift_wrapper_product_type' ).val() || null == $( '#gtw_settings_order_gift_wrapper_product' ).val() ) {
					error_message = gtw_admin_params.product_creation_error_message ;
				}
			}

			if ( error_message ) {
				alert( error_message ) ;
				event.preventDefault( ) ;
				return false ;
			}

		} , product_design_type : function ( $this ) {
			if ( '2' == $( $this ).val() ) {
				$( '.gtw-product-designs' ).show() ;
			} else {
				$( '.gtw-product-designs' ).hide() ;
			}
		} , gift_wrapper_design_type : function ( $this ) {
			if ( '2' == $( $this ).val() ) {
				$( '#gtw_settings_gift_wrapper_design_price' ).closest( 'tr' ).show( ) ;
			} else {
				$( '#gtw_settings_gift_wrapper_design_price' ).closest( 'tr' ).hide( ) ;
			}
		} , product_gift_wrapper : function ( $this ) {

			if ( $( $this ).is( ':checked' ) ) {
				$( '.gtw-product-gift-wrapper-field' ).closest( 'tr' ).show() ;

			} else {
				$( '.gtw-product-gift-wrapper-field' ).closest( 'tr' ).hide() ;
			}
		} , order_gift_wrapper : function ( $this ) {
			var cart_order_gift_wrapper = $( '#gtw_settings_enable_cart_order_gift_wrapper' ) ,
					checkout_order_gift_wrapper = $( "#gtw_settings_enable_checkout_order_gift_wrapper" ) ;

			if ( cart_order_gift_wrapper.is( ':checked' ) && checkout_order_gift_wrapper.is( ':checked' ) ) {
				$( '.gtw-order-gift-wrapper-field' ).closest( 'tr' ).show() ;
				FGF_Admin.order_gift_wrapper_product_type( '#gtw_settings_order_gift_wrapper_product_type' ) ;
				FGF_Admin.order_gift_wrapper_remove_notice( '#gtw_settings_show_order_gift_wrapper_remove_notice' ) ;
			} else if ( cart_order_gift_wrapper.is( ':checked' ) ) {
				$( '.gtw-order-gift-wrapper-field' ).closest( 'tr' ).show() ;
				$( '.gtw-order-gift-wrapper-checkout-field' ).closest( 'tr' ).hide() ;
				FGF_Admin.order_gift_wrapper_product_type( '#gtw_settings_order_gift_wrapper_product_type' ) ;
				FGF_Admin.order_gift_wrapper_remove_notice( '#gtw_settings_show_order_gift_wrapper_remove_notice' ) ;
			} else if ( checkout_order_gift_wrapper.is( ':checked' ) ) {
				$( '.gtw-order-gift-wrapper-field' ).closest( 'tr' ).show() ;
				$( '.gtw-order-gift-wrapper-cart-field' ).closest( 'tr' ).hide() ;
				FGF_Admin.order_gift_wrapper_product_type( '#gtw_settings_order_gift_wrapper_product_type' ) ;
				FGF_Admin.order_gift_wrapper_remove_notice( '#gtw_settings_show_order_gift_wrapper_remove_notice' ) ;
			} else {
				$( '.gtw-order-gift-wrapper-field' ).closest( 'tr' ).hide() ;
			}
		} , order_gift_wrapper_product_type : function ( $this ) {
			if ( '1' == $( $this ).val() ) {
				$( '#gtw_settings_order_gift_wrapper_product_name' ).closest( 'tr' ).show( ) ;
				$( '#gtw_settings_create_order_gift_wrapper_product' ).closest( 'tr' ).show( ) ;
				$( '#gtw_settings_order_gift_wrapper_product' ).closest( 'tr' ).hide( ) ;
			} else {
				$( '#gtw_settings_order_gift_wrapper_product_name' ).closest( 'tr' ).hide( ) ;
				$( '#gtw_settings_create_order_gift_wrapper_product' ).closest( 'tr' ).hide( ) ;
				$( '#gtw_settings_order_gift_wrapper_product' ).closest( 'tr' ).show( ) ;
			}
		} , order_gift_wrapper_remove_notice : function ( $this ) {
			if ( '1' == $( $this ).val() ) {
				$( '#gtw_settings_order_gw_remove_notice_display_type' ).closest( 'tr' ).show( ) ;
			} else {
				$( '#gtw_settings_order_gw_remove_notice_display_type' ).closest( 'tr' ).hide( ) ;
			}
		} , product_restriction : function ( $this ) {
			$( '.gtw_product_restriction' ).closest( 'tr' ).hide() ;
			if ( $( $this ).val() === '2' ) {
				$( '#gtw_settings_gift_wrapping_include_product' ).closest( 'tr' ).show() ;
			} else if ( $( $this ).val() === '3' ) {
				$( '#gtw_settings_gift_wrapping_exclude_product' ).closest( 'tr' ).show() ;
			} else if ( $( $this ).val() === '5' ) {
				$( '#gtw_settings_gift_wrapping_include_categories' ).closest( 'tr' ).show() ;
			} else if ( $( $this ).val() === '6' ) {
				$( '#gtw_settings_gift_wrapping_exclude_categories' ).closest( 'tr' ).show() ;
			}
		} , add_new_rule : function ( event ) {
			event.preventDefault() ;

			var table = $( '.gtw-gift-wrapper-designs-table' ) ,
					rule_count = table.find( '.gtw-rule-count' ).length ;

			FGF_Admin.block( table ) ;

			var data = ( {
				action : 'gtw_add_new_rule' ,
				key : rule_count + 1 ,
				gtw_security : gtw_admin_params.rules_nonce ,
			} ) ;

			$.post( ajaxurl , data , function ( res ) {
				if ( true === res.success ) {
					table.find( 'tbody' ).append( res.data.html ) ;
				} else {
					alert( res.data.error ) ;
				}

				FGF_Admin.unblock( table ) ;
			}
			) ;
		} , remove_rule : function ( event ) {
			event.preventDefault() ;

			if ( !confirm( gtw_admin_params.remove_rule_alert_message ) ) {
				return ;
			}

			var table = $( '.gtw-gift-wrapper-designs-table' ) ,
					$this = $( event.currentTarget ) ,
					rule_id = $( $this ).data( 'rule-id' ) ;

			FGF_Admin.block( table ) ;

			if ( rule_id == '' ) {
				$( $this ).closest( 'tr' ).remove() ;
				FGF_Admin.unblock( table ) ;
			} else {
				var data = ( {
					action : 'gtw_remove_rule' ,
					rule_id : rule_id ,
					gtw_security : gtw_admin_params.rules_nonce ,
				} ) ;

				$.post( ajaxurl , data , function ( res ) {
					if ( true === res.success ) {
						$( $this ).closest( 'tr' ).remove() ;
					} else {
						alert( res.data.error ) ;
					}

					FGF_Admin.unblock( table ) ;
				}
				) ;
			}

		} , add_product_new_rule : function ( event ) {
			event.preventDefault() ;

			var table = $( '.gtw-product-gift-wrapper-designs-table' ) ;

			FGF_Admin.block( table ) ;

			var data = ( {
				action : 'gtw_add_product_new_rule' ,
				gtw_security : gtw_admin_params.rules_nonce ,
			} ) ;

			$.post( ajaxurl , data , function ( res ) {
				if ( true === res.success ) {
					table.find( 'tbody' ).append( res.data.html ) ;
				} else {
					alert( res.data.error ) ;
				}

				FGF_Admin.unblock( table ) ;
			}
			) ;
		} , remove_product_rule : function ( event ) {
			event.preventDefault() ;

			if ( !confirm( gtw_admin_params.remove_rule_alert_message ) ) {
				return ;
			}

			var $this = $( event.currentTarget ) ;

			$( $this ).closest( 'tr' ).remove() ;
		} , create_order_gift_wrapper_product : function ( event ) {
			event.preventDefault( ) ;

			var $this = $( event.currentTarget ) ;

			var product_name = $( '#gtw_settings_order_gift_wrapper_product_name' ).val( ) ;

			if ( product_name == '' ) {
				alert( gtw_admin_params.empty_product_name_message ) ;
				return false ;
			}

			if ( !confirm( gtw_admin_params.confirm_product_creation_message ) ) {
				return false ;
			}

			FGF_Admin.block( $this ) ;

			var data = {
				action : 'gtw_create_order_gift_wrapper_product' ,
				product_name : product_name ,
				gtw_security : gtw_admin_params.product_nonce ,
			}
			$.post( ajaxurl , data , function ( res ) {
				if ( true === res.success ) {
					$( '#gtw_settings_order_gift_wrapper_product' ).append( $( '<option></option>' ).attr( 'value' , res.data.product_id ).text( res.data.product_name ).prop( 'selected' , true ) )
					$( '#gtw_settings_order_gift_wrapper_product_type' ).val( "2" ).trigger( "change" ) ;
				} else {
					alert( res.data.error ) ;
				}

				FGF_Admin.unblock( $this ) ;
			} ) ;
		} , select_image : function ( event ) {
			event.preventDefault( ) ;
			// Upload Batch Image.
			var file_frame ;
			var $button = $( this ) ;
			var formfield = $( this ).prev( ) ;
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open( ) ;
				return ;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media( {
				frame : 'select' ,
				// Set the title of the modal.
				title : $button.data( 'title' ) ,
				multiple : false ,
				library : {
					type : 'image'
				} ,
				button : {
					text : $button.data( 'button' )
				}
			} ) ;
			// When an image is selected, run a callback.
			file_frame.on( 'select' , function ( ) {
				var file_id = '' ;
				var file_path = '' ;
				var selection = file_frame.state( ).get( 'selection' ) ;
				selection.map( function ( attachment ) {
					attachment = attachment.toJSON( ) ;
					if ( attachment.id ) {
						file_id = attachment.id ;
						file_path = attachment.url ;
					}
				} ) ;
				formfield.val( file_id ) ;

				$button.closest( 'td' ).find( '.gtw-gift-wrapper-image-preview img' ).attr( 'src' , file_path ) ;
			} ) ;
			// Finally, open the modal.
			file_frame.open( ) ;
		} , block : function ( id ) {
			$( id ).block( {
				message : null ,
				overlayCSS : {
					background : '#fff' ,
					opacity : 0.7
				}
			} ) ;
		} , unblock : function ( id ) {
			$( id ).unblock() ;
		} ,
	} ;
	FGF_Admin.init( ) ;
} ) ;
