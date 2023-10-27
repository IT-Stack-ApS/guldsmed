<?php

function enqueue_custom_scripts() {
    wp_enqueue_style('slick-css', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css');
    wp_enqueue_script('slick-js', 'https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_custom_scripts');

class Display_Subcategories_Widget extends WP_Widget {

    // Constructor
    public function __construct() {
        parent::__construct(
            'display_subcategories_widget', 
            'Display Subcategories', 
            array('description' => __('A Widget to Display Product Subcategories', 'text_domain'),)
        );
    }

    // The frontend display of the widget
    public function widget($args, $instance) {
        echo $args['before_widget'];
        echo $this->get_subcategories_content();
        echo $args['after_widget'];
    }

    // Fetch and generate the subcategories content
	private function get_subcategories_content() {
		ob_start();
		
		// Set up the default args to hide empty categories.
		$default_args = array(
			'taxonomy' => 'product_cat',
			'hide_empty' => true
		);		

		// Adjust arguments based on whether you're on the shop page or a category page.
		if (is_shop()) {
			$args = array(
				'taxonomy' => 'product_cat',
				'parent' => 0,
				'hide_empty' => true,
				'suppress_filters' => true,
				'orderby' => 'term_order',
				'order' => 'ASC'
			);
		} else {
			$parentid = get_queried_object_id();
			$args = array(
				'taxonomy' => 'product_cat',
				'parent' => $parentid,
				'hide_empty' => true,
				'suppress_filters' => true,
				'orderby' => 'term_order',
				'order' => 'ASC'				
			);
		}

		$terms = get_terms('product_cat', $args);

		if ($terms) {
			if (is_shop()) {
				echo '<div class="product-cats-wrapper swiper-container"><div class="swiper-button-prev"><i class="fas fa-chevron-left"></i></div><div class="swiper-button-next"><i class="fas fa-chevron-right"></i></div><ul class="product-cats swiper-wrapper">';
			} else {
				$term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
				$parent = get_term($term->parent, get_query_var('taxonomy'));
				$children = get_term_children($term->term_id, get_query_var('taxonomy'));

				if (($parent && $parent->term_id == "") && (is_array($children) && sizeof($children) > 0)) {
					echo '<div class="product-cats-wrapper swiper-container"><div class="swiper-button-prev"><i class="fas fa-chevron-left"></i></div><div class="swiper-button-next"><i class="fas fa-chevron-right"></i></div><ul class="product-cats swiper-wrapper">';
				} else {
					echo '<button class="show_cat child-show_cat" type="button" name="show_cat">VIS KATEGORIER</button><div class="product-cats-wrapper child-product-cats-wrapper"><ul class="product-cats">';
				}
			}

			foreach ($terms as $term) {
				if ($term->count > 0) {
					echo '<li class="category swiper-slide">';
					echo '<a href="' . esc_url(get_term_link($term)) . '" class="' . $term->slug . '">';
					woocommerce_subcategory_thumbnail($term);
					echo '<p>';
					echo $term->name;
					echo '</p>';
					echo '</a>';
					echo '</li>';
				}
			}

			echo '</ul></div>';
		}

		return ob_get_clean();
	}
}

// Register the widget
function register_display_subcategories_widget() {
    register_widget('Display_Subcategories_Widget');
}
add_action('widgets_init', 'register_display_subcategories_widget');

//Product Category Slider
function product_cat_slider() {
	register_sidebar(
	  array(
		'id' => 'product-cat-slider-area',
		'name' => esc_html__( 'Product Category Slider', 'theme-domain' ),
		'description' => esc_html__( 'Widgets til Product Cateogory Slider', 'theme-domain' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<div class="widget-title-wrapper"><h3 class="widget-title">',
		'after_title' => '</h3></div>'
	  )
	);
  }
  add_action( 'widgets_init', 'product_cat_slider' );
