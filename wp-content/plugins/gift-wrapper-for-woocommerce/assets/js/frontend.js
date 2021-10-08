/* global gtw_frontend_params */

jQuery( function ( $ ) {
	'use strict' ;

	var GTW_Frontend = {

		init : function ( ) {
			this.trigger_on_page_load() ;

			// Show variation data.
			$( document ).on( 'show_variation' , this.onFoundVariation ) ;
			$( document ).on( 'hide_variation' , this.onResetVariation ) ;

			// Handle product page gift wrapper events.
			$( document ).on( 'change' , '.gtw-product-gift-wrapper-enable' , this.toggle_gift_wrapper ) ;
			$( document ).on( 'keyup , change' , '.gtw-gift-wrapper-message' , this.validate_message_count ) ;
			$( document ).on( 'click' , '.gtw-gift-wrapper-select' , this.select_gift_wrapper_item ) ;
			$( document ).on( 'click' , '.gtw-remove-order-gift-wrapper' , this.remove_order_gift_wrapper ) ;

			//Popup product gift wrapper.
			$( document ).on( 'click' , '.gtw-popup-product-gift-wrapper-item' , this.choose_popup_product_gift_wrapper_item ) ;
			$( document ).on( 'click' , '.gtw-popup-select-product-gift-wrapper' , this.select_popup_product_gift_wrapper_item ) ;
			$( document ).on( 'click' , '.gtw-popup-product-gift-wrapper-pagination .gtw-pagination' , this.popup_product_gift_wrapper_pagination ) ;

			//Popup Order gift wrapper.
			$( document ).on( 'click' , '.gtw-add-order-gift-wrapper' , this.add_order_gift_wrapper_item ) ;
			$( document ).on( 'click' , '.gtw-popup-order-gift-wrapper-item' , this.choose_popup_order_gift_wrapper_item ) ;
			$( document ).on( 'click' , '.gtw-popup-select-order-gift-wrapper' , this.select_popup_order_gift_wrapper_item ) ;
			$( document ).on( 'click' , '.gtw-popup-order-gift-wrapper-pagination .gtw-pagination' , this.popup_order_gift_wrapper_pagination ) ;
			$( document ).on( 'click' , '.gtw-popup-order-exculde-gift-wrapper-pagination .gtw-pagination' , this.popup_order_exclude_gift_wrapper_pagination ) ;


		} , trigger_on_page_load : function ( ) {

			// Handle init events.
			this.handle_gift_wrapper( $( '.gtw-product-gift-wrapper-enable' ) ) ;

		} , onFoundVariation : function ( evt , variation , purchasable ) {
			GTW_Frontend.onResetVariation() ;

			if ( variation.gtw_gift_wrapper ) {
				$( '.variations_form' ).find( '.woocommerce-variation-add-to-cart' ).before( variation.gtw_gift_wrapper ) ;
			}

			GTW_Frontend.handle_gift_wrapper( $( '.gtw-product-gift-wrapper-enable' ) ) ;

			$( '.gtw-product-gift-wrapper-product-id' ).val( variation.variation_id ) ;

			$( document.body ).trigger( 'gtw-enhanced-lightcase' ) ;
		} , onResetVariation : function ( evt ) {
			if ( $( '.variations_form' ).find( '.gtw-product-gift-wrapper' ).length ) {
				$( '.variations_form' ).find( '.gtw-product-gift-wrapper' ).remove() ;
			}
		} , validate_message_count : function ( event ) {
			var $this = $( event.currentTarget ) ,
					message = $( $this ) ,
					message_count = message.val().length ,
					message_content = $( $this ).closest( '.gtw-gift-wrapper-field' ) ,
					total_message_count = message_content.find( '.gtw-gift-wrapper-message-count' ).data( 'max' ) ;

			if ( message_count <= total_message_count ) {
				message_content.find( '.gtw-gift-wrapper-message-count' ).html( total_message_count - message_count ) ;
			} else {
				message.val( message.val().substring( 0 , total_message_count ) ) ;
			}

			return true ;

		} , toggle_gift_wrapper : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ;

			GTW_Frontend.handle_gift_wrapper( $this ) ;

		} , handle_gift_wrapper : function ( $this ) {
			var gift_wrapper = $( '.gtw-product-gift-wrapper-content' ) ;

			if ( $( $this ).is( ':checked' ) ) {
				gift_wrapper.find( '.gtw-gift-wrapper' ).show() ;
			} else {
				gift_wrapper.find( '.gtw-gift-wrapper' ).hide() ;
			}
		} , select_gift_wrapper_item : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ,
					gift_wrapper = $( '.gtw-product-gift-wrapper-content' ) ,
					product_id = gift_wrapper.find( '.gtw-product-gift-wrapper-product-id' ).val() ,
					wrapper_items = gift_wrapper.find( '.gtw-product-gift-wrapper-items-content' ) ,
					wrapper_item = gift_wrapper.find( '.gtw-product-gift-wrapper-item' ) ;

			GTW_Frontend.block( wrapper_items ) ;

			var data = ( {
				action : 'gtw_select_gift_wrapper' ,
				rule_id : $( $this ).data( 'rule-id' ) ,
				product_id : product_id ,
				gtw_security : gtw_frontend_params.gift_wrapper_nonce ,
			} ) ;

			$.post( gtw_frontend_params.ajaxurl , data , function ( res ) {

				if ( true === res.success ) {
					wrapper_item.removeClass( 'gtw_current' ) ;
					gift_wrapper.find( '.gtw-product-gift-wrapper-current-item' ).val( $( $this ).data( 'rule-id' ) ) ;
					gift_wrapper.find( '.gtw-product-gift-wrapper-name' ).html( res.data.name ) ;
					gift_wrapper.find( '.gtw-product-gift-wrapper-price strong' ).html( res.data.price ) ;
					gift_wrapper.find( '.gtw-product-gift-wrapper-total-payable strong' ).html( res.data.total ) ;
					$( $this ).addClass( 'gtw_current' ) ;
				} else {
					alert( res.data.error ) ;
				}

				GTW_Frontend.unblock( wrapper_items ) ;
			}
			) ;

		} , choose_popup_product_gift_wrapper_item : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ,
					gift_wrapper = $( '.gtw-product-gift-wrapper-content' ) ,
					wrapper_modal = $( $this ).closest( '.gtw-popup-product-gift-wrapper-modal' ) ,
					wrapper_item = wrapper_modal.find( '.gtw-popup-product-gift-wrapper-list' ) ,
					wrapper_content = wrapper_modal.find( '.gtw-popup-product-gift-wrapper-content' ) ,
					product_id = gift_wrapper.find( '.gtw-product-gift-wrapper-product-id' ).val() ;

			GTW_Frontend.block( wrapper_content ) ;

			var data = ( {
				action : 'gtw_select_popup_product_gift_wrapper' ,
				rule_id : $( $this ).data( 'rule-id' ) ,
				product_id : product_id ,
				gtw_security : gtw_frontend_params.gift_wrapper_nonce ,
			} ) ;

			$.post( gtw_frontend_params.ajaxurl , data , function ( res ) {

				if ( true === res.success ) {
					wrapper_item.removeClass( 'gtw_current' ) ;
					wrapper_modal.find( '.gtw-popup-product-gift-wrapper-current-item' ).val( $( $this ).data( 'rule-id' ) ) ;
					wrapper_modal.find( '.gtw-product-gift-wrapper-name' ).html( res.data.name ) ;
					wrapper_modal.find( '.gtw-product-gift-wrapper-price strong' ).html( res.data.price ) ;
					$( $this ).addClass( 'gtw_current' ) ;
				} else {
					alert( res.data.error ) ;
				}

				GTW_Frontend.unblock( wrapper_content ) ;
			} ) ;

		} , remove_order_gift_wrapper : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ;

			if ( !confirm( gtw_frontend_params.remove_order_gift_wrapper_msg ) ) {
				return false ;
			}
			GTW_Frontend.block( $this ) ;

			var data = ( {
				action : 'gtw_remove_order_gift_wrapper' ,
				gtw_security : gtw_frontend_params.gift_wrapper_nonce ,
			} ) ;

			$.post( gtw_frontend_params.ajaxurl , data , function ( res ) {

				if ( true === res.success ) {
					window.location.reload() ;
				} else {
					alert( res.data.error ) ;
				}

				GTW_Frontend.unblock( $this ) ;
			} ) ;

		} , select_popup_product_gift_wrapper_item : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ,
					popup_gift_wrapper_modal = $( '.gtw-popup-product-gift-wrapper-modal' ) ,
					gift_wrapper = $( '.gtw-product-gift-wrapper-content' ) ,
					wrapper_item = gift_wrapper.find( '.gtw-product-gift-wrapper-item' ) ,
					product_id = gift_wrapper.find( '.gtw-product-gift-wrapper-product-id' ).val() ,
					current_item = popup_gift_wrapper_modal.find( '.gtw-popup-product-gift-wrapper-current-item' ).val() ;

			GTW_Frontend.block( popup_gift_wrapper_modal ) ;

			var data = ( {
				action : 'gtw_select_popup_product_gift_wrapper_item' ,
				rule_id : current_item ,
				product_id : product_id ,
				gtw_security : gtw_frontend_params.popup_gift_wrapper_nonce ,
			} ) ;

			$.post( gtw_frontend_params.ajaxurl , data , function ( res ) {

				if ( true === res.success ) {
					wrapper_item.removeClass( 'gtw_current' ) ;
					gift_wrapper.find( '.gtw-product-gift-wrapper-current-item' ).val( current_item ) ;
					gift_wrapper.find( '.gtw-product-gift-wrapper-name' ).html( res.data.name ) ;
					gift_wrapper.find( '.gtw-product-gift-wrapper-price strong' ).html( res.data.price ) ;
					gift_wrapper.find( '.gtw-product-gift-wrapper-total-payable strong' ).html( res.data.total ) ;

					if ( $( '.gtw-product-gift-wrapper-item-' + current_item ).length ) {
						$( '.gtw-product-gift-wrapper-item-' + current_item ).remove() ;
					} else {
						$( '.gtw-gift-wrapper-select:last' ).remove() ;
					}

					gift_wrapper.find( '.gtw-product-gift-wrapper-items' ).prepend( res.data.item ) ;
					lightcase.close() ;
				} else {
					alert( res.data.error ) ;
				}

				GTW_Frontend.unblock( popup_gift_wrapper_modal ) ;
			}
			) ;

		} , popup_product_gift_wrapper_pagination : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ,
					wrapper_content = $this.closest( '.gtw-popup-product-gift-wrapper-content' ) ,
					gift_items = wrapper_content.find( '.gtw-popup-product-gift-wrapper-items' ) ,
					current_page = $( $this ).data( 'page' ) ;

			GTW_Frontend.block( wrapper_content ) ;

			var data = ( {
				action : 'gtw_popup_product_gift_wrapper_pagination' ,
				page_number : current_page ,
				product_id : wrapper_content.find( 'gtw-product-gift-wrapper-product-id' ).val() ,
				gtw_security : gtw_frontend_params.popup_gift_wrapper_nonce ,
			} ) ;

			$.post( gtw_frontend_params.ajaxurl , data , function ( res ) {

				if ( true === res.success ) {
					gift_items.html( res.data.html ) ;

					GTW_Frontend.handle_pagination( current_page , wrapper_content ) ;
				} else {
					alert( res.data.error ) ;
				}

				GTW_Frontend.unblock( wrapper_content ) ;
			}
			) ;
		} , choose_popup_order_gift_wrapper_item : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ,
					wrapper_modal = $( $this ).closest( '.gtw-popup-order-gift-wrapper-modal' ) ,
					wrapper_item = wrapper_modal.find( '.gtw-popup-order-gift-wrapper-list' ) ,
					wrapper_content = wrapper_modal.find( '.gtw-popup-order-gift-wrapper-content' ) ;

			GTW_Frontend.block( wrapper_content ) ;

			var data = ( {
				action : 'gtw_select_popup_order_gift_wrapper' ,
				rule_id : $( $this ).data( 'rule-id' ) ,
				gtw_security : gtw_frontend_params.gift_wrapper_nonce ,
			} ) ;

			$.post( gtw_frontend_params.ajaxurl , data , function ( res ) {

				if ( true === res.success ) {
					wrapper_item.removeClass( 'gtw_current' ) ;
					wrapper_modal.find( '.gtw-popup-order-gift-wrapper-current-item' ).val( $( $this ).data( 'rule-id' ) ) ;
					wrapper_modal.find( '.gtw-product-gift-wrapper-name' ).html( res.data.name ) ;
					wrapper_modal.find( '.gtw-product-gift-wrapper-price strong' ).html( res.data.price ) ;
					$( $this ).addClass( 'gtw_current' ) ;
				} else {
					alert( res.data.error ) ;
				}

				GTW_Frontend.unblock( wrapper_content ) ;
			} ) ;

		} , add_order_gift_wrapper_item : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ;

			GTW_Frontend.block( $this ) ;

			var data = ( {
				action : 'gtw_add_order_gift_wrapper_item' ,
				gtw_security : gtw_frontend_params.popup_gift_wrapper_nonce ,
			} ) ;

			$.post( gtw_frontend_params.ajaxurl , data , function ( res ) {

				if ( true === res.success ) {
					window.location.reload() ;
				} else {
					alert( res.data.error ) ;
				}

				GTW_Frontend.unblock( $this ) ;
			}
			) ;

		} , select_popup_order_gift_wrapper_item : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ,
					popup_gift_wrapper_modal = $( '.gtw-popup-order-gift-wrapper-modal' ) ,
					current_item = popup_gift_wrapper_modal.find( '.gtw-popup-order-gift-wrapper-current-item' ).val() ,
					fields = popup_gift_wrapper_modal.find( "input,textarea" ).serialize() ;

			GTW_Frontend.block( popup_gift_wrapper_modal ) ;

			var data = ( {
				action : 'gtw_select_popup_order_gift_wrapper_item' ,
				rule_id : current_item ,
				fields : fields ,
				gtw_security : gtw_frontend_params.popup_gift_wrapper_nonce ,
			} ) ;

			$.post( gtw_frontend_params.ajaxurl , data , function ( res ) {

				if ( true === res.success ) {
					lightcase.close() ;
					window.location.reload() ;
				} else {
					alert( res.data.error ) ;
				}

				GTW_Frontend.unblock( popup_gift_wrapper_modal ) ;
			}
			) ;

		} , popup_order_gift_wrapper_pagination : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ,
					wrapper_content = $this.closest( '.gtw-popup-order-gift-wrapper-content' ) ,
					gift_items = wrapper_content.find( '.gtw-popup-order-gift-wrapper-items' ) ,
					current_page = $this.data( 'page' ) ;

			GTW_Frontend.block( wrapper_content ) ;

			var data = ( {
				action : 'gtw_popup_order_gift_wrapper_pagination' ,
				page_number : current_page ,
				gtw_security : gtw_frontend_params.popup_gift_wrapper_nonce ,
			} ) ;

			$.post( gtw_frontend_params.ajaxurl , data , function ( res ) {

				if ( true === res.success ) {
					gift_items.html( res.data.html ) ;

					GTW_Frontend.handle_pagination( current_page , wrapper_content ) ;
				} else {
					alert( res.data.error ) ;
				}

				GTW_Frontend.unblock( wrapper_content ) ;
			}
			) ;
		} , popup_order_exclude_gift_wrapper_pagination : function ( event ) {
			event.preventDefault( ) ;
			var $this = $( event.currentTarget ) ,
					table = $this.closest( '.gtw-popup-order-exclude-items-table' ) ,
					gift_items = table.find( 'tbody' ) ,
					current_page = $this.data( 'page' ) ;

			GTW_Frontend.block( table ) ;

			var data = ( {
				action : 'gtw_popup_order_exclude_gift_wrapper_pagination' ,
				page_number : current_page ,
				gtw_security : gtw_frontend_params.popup_gift_wrapper_nonce ,
			} ) ;

			$.post( gtw_frontend_params.ajaxurl , data , function ( res ) {

				if ( true === res.success ) {
					gift_items.html( res.data.html ) ;

					GTW_Frontend.handle_pagination( current_page , table ) ;
				} else {
					alert( res.data.error ) ;
				}

				GTW_Frontend.unblock( table ) ;
			}
			) ;
		} , handle_pagination : function ( current_page , wrapper ) {

			var next_pagination = current_page + 1 ,
					prev_pagination = current_page - 1 ,
					last_pagination = wrapper.find( '.gtw-last-pagination' ).data( 'page' ) ;

			if ( prev_pagination <= 0 ) {
				prev_pagination = 1 ;
			}

			if ( last_pagination < next_pagination ) {
				next_pagination = last_pagination ;
			}

			wrapper.find( '.gtw-pagination' ).removeClass( 'current' ) ;
			wrapper.find( '.gtw-page-number span[data-page=' + current_page + ']' ).addClass( 'current' ) ;

			wrapper.find( '.gtw-next-pagination' ).data( 'page' , next_pagination ) ;
			wrapper.find( '.gtw-prev-pagination' ).data( 'page' , prev_pagination ) ;
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
	GTW_Frontend.init( ) ;
} ) ;
