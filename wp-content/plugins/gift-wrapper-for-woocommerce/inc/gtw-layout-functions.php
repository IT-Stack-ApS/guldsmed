<?php
/**
 * Layout functions.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit ; // Exit if accessed directly.
}

if ( ! function_exists( 'gtw_select2_html' ) ) {

	/**
	 * Return or display Select2 HTML.
	 *
	 * @return string
	 */
	function gtw_select2_html( $args, $echo = true ) {
		$args = wp_parse_args(
				$args , array(
			'class'                   => '' ,
			'id'                      => '' ,
			'name'                    => '' ,
			'list_type'               => '' ,
			'action'                  => '' ,
			'placeholder'             => '' ,
			'exclude_global_variable' => 'no' ,
			'custom_attributes'       => array() ,
			'multiple'                => true ,
			'allow_clear'             => true ,
			'selected'                => true ,
			'options'                 => array() ,
				)
				) ;

		$multiple = $args[ 'multiple' ] ? 'multiple="multiple"' : '' ;
		$name     = esc_attr( '' !== $args[ 'name' ] ? $args[ 'name' ] : $args[ 'id' ] ) . '[]' ;
		$options  = array_filter( gtw_check_is_array( $args[ 'options' ] ) ? $args[ 'options' ] : array() ) ;

		$allowed_html = array(
			'select' => array(
				'id'                           => array() ,
				'class'                        => array() ,
				'data-placeholder'             => array() ,
				'data-allow_clear'             => array() ,
				'data-exclude-global-variable' => array() ,
				'data-action'                  => array() ,
								'data-nonce'                   => array() ,
				'multiple'                     => array() ,
				'name'                         => array() ,
			) ,
			'option' => array(
				'value'    => array() ,
				'selected' => array()
			)
				) ;

		// Custom attribute handling.
		$custom_attributes = gtw_format_custom_attributes( $args ) ;
				$data_nonce        = ( 'products' == $args[ 'list_type' ] ) ? 'data-nonce="' . wp_create_nonce( 'search-products' ) . '"' : '' ;

		ob_start() ;
		?><select <?php echo esc_attr( $multiple ) ; ?> 
			name="<?php echo esc_attr( $name ) ; ?>" 
			id="<?php echo esc_attr( $args[ 'id' ] ) ; ?>" 
			data-action="<?php echo esc_attr( $args[ 'action' ] ) ; ?>" 
			data-exclude-global-variable="<?php echo esc_attr( $args[ 'exclude_global_variable' ] ) ; ?>" 
			class="gtw_select2_search <?php echo esc_attr( $args[ 'class' ] ) ; ?>" 
			data-placeholder="<?php echo esc_attr( $args[ 'placeholder' ] ) ; ?>" 
			<?php echo wp_kses( implode( ' ' , $custom_attributes ) , $allowed_html ) ; ?>
						<?php echo wp_kses( $data_nonce , $allowed_html ) ; ?>
			<?php echo $args[ 'allow_clear' ] ? 'data-allow_clear="true"' : '' ; ?> >
				<?php
				if ( is_array( $args[ 'options' ] ) ) {
					foreach ( $args[ 'options' ] as $option_id ) {
						$option_value = '' ;
						switch ( $args[ 'list_type' ] ) {
							case 'post':
								$option_value = get_the_title( $option_id ) ;
								break ;
							case 'products':
								$product      = wc_get_product( $option_id ) ;
								if ( $product ) {
									$option_value = $product->get_name() . ' (#' . absint( $option_id ) . ')' ;
								}
								break ;
							case 'customers':
								$user = get_user_by( 'id' , $option_id ) ;
								if ( $user ) {
									$option_value = $user->display_name . '(#' . absint( $user->ID ) . ' &ndash; ' . $user->user_email . ')' ;
								}
								break ;
						}

						if ( $option_value ) {
							?>
						<option value="<?php echo esc_attr( $option_id ) ; ?>" <?php echo $args[ 'selected' ] ? 'selected="selected"' : '' ; // WPCS: XSS ok. ?>><?php echo esc_html( $option_value ) ; ?></option>
							<?php
						}
					}
				}
				?>
		</select>
		<?php
		$html = ob_get_clean() ;

		if ( $echo ) {
			echo wp_kses( $html , $allowed_html ) ;
		}

		return $html ;
	}

}

if ( ! function_exists( 'gtw_format_custom_attributes' ) ) {

	/**
	 * Format Custom Attributes.
	 *
	 * @return array
	 */
	function gtw_format_custom_attributes( $value ) {
		$custom_attributes = array() ;

		if ( ! empty( $value[ 'custom_attributes' ] ) && is_array( $value[ 'custom_attributes' ] ) ) {
			foreach ( $value[ 'custom_attributes' ] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '=' . esc_attr( $attribute_value ) . '' ;
			}
		}

		return $custom_attributes ;
	}

}

if ( ! function_exists( 'gtw_get_template' ) ) {

	/**
	 *  Get other templates from themes.
	 * 
	 * @return void
	 */
	function gtw_get_template( $template_name, $args = array() ) {

		wc_get_template( $template_name , $args , GTW_FOLDER_NAME . '/' , GTW()->templates() ) ;
	}

}

if ( ! function_exists( 'gtw_get_template_html' ) ) {

	/**
	 *  Like gtw_get_template, but returns the HTML instead of outputting.
	 *
	 *  @return string
	 */
	function gtw_get_template_html( $template_name, $args = array() ) {

		ob_start() ;
		gtw_get_template( $template_name , $args ) ;
		return ob_get_clean() ;
	}

}

if ( ! function_exists( 'gtw_price' ) ) {

	/**
	 *  Display Price based wc_price function.
	 *
	 *  @return string
	 */
	function gtw_price( $price, $echo = false ) {

		if ( $echo ) {
			echo wp_kses_post( wc_price( $price ) ) ;
		}

		return wc_price( $price ) ;
	}

}


if ( ! function_exists( 'gtw_get_action_link' ) ) {

	/**
	 * Get the action link.
	 *
	 * @return string
	 */
	function gtw_get_action_link( $status, $id, $current_url, $action = false ) {
		switch ( $status ) {
			case 'edit':
				$status_name = esc_html__( 'Edit' , 'gift-wrapper-for-woocommerce' ) ;
				break ;
			case 'active':
				$status_name = esc_html__( 'Enable' , 'gift-wrapper-for-woocommerce' ) ;
				break ;
			case 'inactive':
				$status_name = esc_html__( 'Disable' , 'gift-wrapper-for-woocommerce' ) ;
				break ;
			default:
				$status_name = esc_html__( 'Delete Permanently' , 'gift-wrapper-for-woocommerce' ) ;
				break ;
		}

		$section_name = 'section' ;
		if ( $action ) {
			$section_name = 'action' ;
		}

		if ( 'edit' == $status ) {
			return '<a href="' . esc_url(
							add_query_arg(
									array(
						$section_name => $status ,
						'id'          => $id ,
									) , $current_url
							)
					) . '">' . $status_name . '</a>' ;
		} elseif ( 'delete' == $status ) {
			return '<a class="gtw_delete_data" href="' . esc_url(
							add_query_arg(
									array(
						'action' => $status ,
						'id'     => $id ,
									) , $current_url
							)
					) . '">' . $status_name . '</a>' ;
		} else {
			return '<a href="' . esc_url(
							add_query_arg(
									array(
						'action' => $status ,
						'id'     => $id ,
									) , $current_url
							)
					) . '">' . $status_name . '</a>' ;
		}
	}

}

if ( ! function_exists( 'gtw_get_status_html' ) ) {

	/**
	 * Get the formatted status html.
	 *
	 * @return string
	 */
	function gtw_get_status_html( $status, $html = true ) {

		$status_object = get_post_status_object( $status ) ;

		if ( ! isset( $status_object ) ) {
			return '' ;
		}

		return $html ? '<mark class="gtw_status_label ' . esc_attr( $status ) . '_status"><span >' . esc_html( $status_object->label ) . '</span></mark>' : esc_html( $status_object->label ) ;
	}

}

if ( ! function_exists( 'gtw_get_field_type_name' ) ) {

	/**
	 *  Get the field type Name.
	 *
	 *  @return string
	 */
	function gtw_get_field_type_name( $type ) {

		$types = array(
			'text'     => esc_html__( 'Text' , 'gift-wrapper-for-woocommerce' ) ,
			'textarea' => esc_html__( 'Textarea' , 'gift-wrapper-for-woocommerce' )
				) ;

		if ( ! isset( $types[ $type ] ) ) {
			return '' ;
		}

		return $types[ $type ] ;
	}

}

if ( ! function_exists( 'gtw_wc_help_tip' ) ) {

	/**
	 *  Display the tool help based on WC help tip.
	 *
	 *  @return string
	 */
	function gtw_wc_help_tip( $tip, $allow_html = false, $echo = true ) {

		$formatted_tip = wc_help_tip( $tip , $allow_html ) ;

		if ( $echo ) {
			echo wp_kses_post( $formatted_tip ) ;
		}

		return $formatted_tip ;
	}

}
