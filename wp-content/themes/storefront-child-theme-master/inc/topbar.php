<?php

/* Add custom selling point to theme customization */

function storefrontchild_register_theme_customizer( $wp_customize ) {
	// Create custom panel.
	$wp_customize->add_panel( 'text_blocks', array(
		'priority'       => 500,
		'theme_supports' => '',
		'title'          => __( 'Topbar', 'storefrontchild' ),
		'description'    => __( 'Tilføj selling point til headeren.', 'storefrontchild' ),
	) );
	// Add Selling point text
	// Add section 1.
	$wp_customize->add_section( 'custom_selling_point_1' , array(
		'title'    => __('Selling point 1','storefrontchild'),
		'panel'    => 'text_blocks',
		'priority' => 10
    ) );
    // Add section 2.
    $wp_customize->add_section( 'custom_selling_point_2' , array(
		'title'    => __('Selling point 2','storefrontchild'),
		'panel'    => 'text_blocks',
		'priority' => 10
    ) );
    // Add section 3.
    $wp_customize->add_section( 'custom_selling_point_3' , array(
		'title'    => __('Selling point 3','storefrontchild'),
		'panel'    => 'text_blocks',
		'priority' => 10
    ) );
	// Add setting 1
	$wp_customize->add_setting( 'header_selling_block_1', array(
		 'default'           => __( 'Selling point 1', 'storefrontchild' ),
		 'sanitize_callback' => 'sanitize_text'
    ) );
    // Add setting icon 1
	$wp_customize->add_setting( 'header_selling_icon_1', array(
        'default'           => __( 'fas fa-bicycle', 'storefrontchild' ),
        'sanitize_callback' => 'sanitize_text'
    ) );
    // Add setting 2
    $wp_customize->add_setting( 'header_selling_block_2', array(
        'default'           => __( 'Selling point 2', 'storefrontchild' ),
        'sanitize_callback' => 'sanitize_text'
    ) );
    // Add setting icon 2
	$wp_customize->add_setting( 'header_selling_icon_2', array(
        'default'           => __( 'fas fa-bicycle', 'storefrontchild' ),
        'sanitize_callback' => 'sanitize_text'
    ) );
    // Add setting 3
    $wp_customize->add_setting( 'header_selling_block_3', array(
        'default'           => __( 'Selling point 3', 'storefrontchild' ),
        'sanitize_callback' => 'sanitize_text'
    ) );
    // Add setting icon 3
	$wp_customize->add_setting( 'header_selling_icon_3', array(
        'default'           => __( 'fas fa-bicycle', 'storefrontchild' ),
        'sanitize_callback' => 'sanitize_text'
    ) );

	// Add control 1
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_point_1',
		    array(
		        'label'    => __( 'Selling Point', 'storefrontchild' ),
		        'section'  => 'custom_selling_point_1',
		        'settings' => 'header_selling_block_1',
		        'type'     => 'text'
            )
	    )
    );

    // Add control icon 1
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_icon_1',
		    array(
                'label'    => __( 'Selling icon', 'storefrontchild' ),
                'description'   => __('Indtast klassen for Font Awesome ikonet. Se ikonerne her: https://fontawesome.com/icons?d=gallery&m=free', 'storefrontchild'),
		        'section'  => 'custom_selling_point_1',
		        'settings' => 'header_selling_icon_1',
		        'type'     => 'text'
		    )
	    )
    );

    // Add control 2
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_point_2',
		    array(
		        'label'    => __( 'Selling Point', 'storefrontchild' ),
		        'section'  => 'custom_selling_point_2',
		        'settings' => 'header_selling_block_2',
		        'type'     => 'text'
		    )
	    )
    );

    // Add control icon 2
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_icon_2',
		    array(
                'label'    => __( 'Selling icon', 'storefrontchild' ),
                'description'   => __('Indtast klassen for Font Awesome ikonet. Se ikonerne her: https://fontawesome.com/icons?d=gallery&m=free', 'storefrontchild'),
		        'section'  => 'custom_selling_point_2',
		        'settings' => 'header_selling_icon_2',
		        'type'     => 'text'
		    )
	    )
    );


    // Add control 3
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_point_3',
		    array(
		        'label'    => __( 'Selling Point', 'storefrontchild' ),
		        'section'  => 'custom_selling_point_3',
		        'settings' => 'header_selling_block_3',
		        'type'     => 'text'
            )
	    )
    );

    // Add control icon 3
	$wp_customize->add_control( new WP_Customize_Control(
	    $wp_customize,
		'custom_selling_icon_3',
		    array(
                'label'         => __( 'Selling icon', 'storefrontchild' ),
                'description'   => __('Indtast klassen for Font Awesome ikonet. Se ikonerne her: https://fontawesome.com/icons?d=gallery&m=free', 'storefrontchild'),
		        'section'       => 'custom_selling_point_3',
		        'settings'      => 'header_selling_icon_3',
		        'type'          => 'text'
		    )
	    )
    );


 	// Sanitize text
	function sanitize_text( $text ) {
	    return sanitize_text_field( $text );
	}
}
add_action( 'customize_register', 'storefrontchild_register_theme_customizer' );
