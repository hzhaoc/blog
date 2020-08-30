<?php
/**
 * Theme Customizer
 *
 * @package Prime Hosting
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function prime_hosting_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$wp_customize->get_control( 'display_header_text' )->label = __( 'Display Site Title', 'prime-hosting' );

	if ( isset( $wp_customize->selective_refresh ) ) {
		$wp_customize->selective_refresh->add_partial(
			'blogname',
			array(
				'selector'        => '.site-title a',
				'render_callback' => 'prime_hosting_customize_partial_blogname',
			)
		);
		$wp_customize->selective_refresh->add_partial(
			'blogdescription',
			array(
				'selector'        => '.site-description',
				'render_callback' => 'prime_hosting_customize_partial_blogdescription',
			)
		);
	}

	$wp_customize->add_section(
		'layout_section',
		array(
			'title'    => __( 'Layout (site width) ', 'prime-hosting' ),
			'priority' => 30,
		)
	);

	$wp_customize->add_section(
		'header_content',
		array(
			'title'    => __( 'Header Settings', 'prime-hosting' ),
			'priority' => 40,
		)
	);

	$wp_customize->add_section(
		'image_section',
		array(
			'title' => __( 'Featured Image Settings', 'prime-hosting' ),
		)
	);

	$wp_customize->add_section(
		'footer_section',
		array(
			'title' => __( 'Footer Settings', 'prime-hosting' ),
		)
	);

	$wp_customize->add_section(
		'social_options',
		array(
			'title' => __( 'Social Sharing', 'prime-hosting' ),
		)
	);

	$wp_customize->add_section(
		'social_links',
		array(
			'title' => __( 'Social Links', 'prime-hosting' ),
		)
	);

	$wp_customize->add_setting(
		'enable_mobile_logo',
		array(
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
			'default'           => false,
		)
	);

	$wp_customize->add_control(
		'enable_mobile_logo',
		array(
			'type'     => 'checkbox',
			'label'    => __( 'Enable mobile logo', 'prime-hosting' ),
			'section'  => 'title_tagline',
			'priority' => 10,
		)
	);

	$wp_customize->add_setting(
		'enable_footer_logo',
		array(
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
			'default'           => false,
		)
	);

	$wp_customize->add_control(
		'enable_footer_logo',
		array(
			'type'     => 'checkbox',
			'label'    => __( 'Display logo in footer', 'prime-hosting' ),
			'section'  => 'title_tagline',
			'priority' => 10,
		)
	);

	$wp_customize->add_setting(
		'mobile_logo',
		array(
			'sanitize_callback' => 'esc_url_raw',
		)
	);

	$wp_customize->add_control(
		new WP_Customize_Image_Control(
			$wp_customize,
			'mobile_logo',
			array(
				'label'           => __( 'Upload a mobile logo', 'prime-hosting' ),
				'section'         => 'title_tagline',
				'active_callback' => function() {
					return get_theme_mod( 'enable_mobile_logo', true );
				},

			)
		)
	);

	$wp_customize->add_setting(
		'display_tagline',
		array(
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'display_tagline',
		array(
			'type'     => 'checkbox',
			'label'    => __( 'Display tagline', 'prime-hosting' ),
			'section'  => 'title_tagline',
			'priority' => 40,
		)
	);

	$wp_customize->add_setting(
		'top_button_text',
		array(
			'sanitize_callback' => 'sanitize_text_field',
			'default'           => '',
		)
	);

	$wp_customize->add_control(
		'top_button_text',
		array(
			'type'        => 'text',
			'label'       => __( 'Header button text:', 'prime-hosting' ),
			'description' => __( 'Leave this setting blank to disable it.', 'prime-hosting' ),
			'input_attrs' => array(
				'placeholder' => __( 'Free Quote', 'prime-hosting' ),
			),
			'section'     => 'header_content',
		)
	);

	$wp_customize->add_setting(
		'top_button_link',
		array(
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		)
	);

	$wp_customize->add_control(
		'top_button_link',
		array(
			'type'    => 'url',
			'label'   => __( 'Header button link:', 'prime-hosting' ),
			'section' => 'header_content',
		)
	);

	$wp_customize->add_setting(
		'show_breadcrumbs',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'show_breadcrumbs',
		array(
			'type'    => 'checkbox',
			'label'   => __( 'Enable breadcrumbs', 'prime-hosting' ),
			'section' => 'header_content',
		)
	);

	$wp_customize->add_setting(
		'show_header_content',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'show_header_content',
		array(
			'type'    => 'checkbox',
			'label'   => __( 'Enable the blog header content on all pages. Disabling this will also hide the header image.', 'prime-hosting' ),
			'section' => 'header_content',
		)
	);

	$wp_customize->add_setting(
		'header_text_heading',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'header_text_heading',
		array(
			'type'        => 'text',
			'label'       => __( 'Enter a heading for the header area:', 'prime-hosting' ),
			'description' => __( 'Leave this setting blank to disable it.', 'prime-hosting' ),
			'section'     => 'header_content',
		)
	);

	$wp_customize->add_setting(
		'header_text',
		array(
			'sanitize_callback' => 'sanitize_textarea_field',
			'default'           => '',
		)
	);

	$wp_customize->add_control(
		'header_text',
		array(
			'type'    => 'textarea',
			'label'   => __( 'Enter a text for the header area:', 'prime-hosting' ),
			'section' => 'header_content',
		)
	);

	$wp_customize->add_setting(
		'container_width',
		array(
			'default'           => 100,
			'sanitize_callback' => 'absint',
		)
	);

	$wp_customize->add_control(
		'container_width',
		array(
			'type'        => 'range',
			'label'       => __( 'Container width', 'prime-hosting' ),
			'description' => __( 'Select a custom width for the website on desktop.', 'prime-hosting' ),
			'input_attrs' => array(
				'min' => 60,
				'max' => 100,
			),
			'section'     => 'layout_section',
		)
	);

	$wp_customize->add_setting(
		'show_footer_widget',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'show_footer_widget',
		array(
			'type'        => 'checkbox',
			'label'       => __( 'Show the newsletter widget area above the footer.', 'prime-hosting' ),
			'description' => __( 'Add a widget to this area to make it visible.', 'prime-hosting' ),
			'section'     => 'footer_section',
		)
	);

	$wp_customize->add_setting(
		'footer_text',
		array(
			'sanitize_callback' => 'sanitize_textarea_field',
		)
	);

	$wp_customize->add_control(
		'footer_text',
		array(
			'type'    => 'textarea',
			'label'   => __( 'Enter a text for the left footer area:', 'prime-hosting' ),
			'section' => 'footer_section',
		)
	);

	$wp_customize->add_setting(
		'show_footer_bar',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'show_footer_bar',
		array(
			'type'        => 'checkbox',
			'label'       => __( 'Enable footer bar', 'prime-hosting' ),
			'description' => __( 'Displays copyright and privacy policy at the bottom of the page.', 'prime-hosting' ),
			'section'     => 'footer_section',
		)
	);

	$wp_customize->add_setting(
		'copyright_text',
		array(
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	$wp_customize->add_control(
		'copyright_text',
		array(
			'type'        => 'text',
			'label'       => __( 'Enter a copyright text:', 'prime-hosting' ),
			'input_attrs' => array(
				'placeholder' => __( 'Copyright: ', 'prime-hosting' ) . gmdate( 'Y' ),
			),
			'section'     => 'footer_section',
		)
	);

	$wp_customize->add_setting(
		'sharing_posts',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'sharing_posts',
		array(
			'label'   => __( 'Show Social Sharing on posts.', 'prime-hosting' ),
			'section' => 'social_options',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'sharing_pages',
		array(
			'default'           => false,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'sharing_pages',
		array(
			'label'   => __( 'Show Social Sharing on pages.', 'prime-hosting' ),
			'section' => 'social_options',
			'type'    => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'sharing_facebook',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'sharing_facebook',
		array(
			'label'       => __( 'Facebook Social Sharing', 'prime-hosting' ),
			'description' => __( 'Enable a Facebook sharing link.', 'prime-hosting' ),
			'section'     => 'social_options',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'sharing_twitter',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'sharing_twitter',
		array(
			'label'       => __( 'Twitter Social Sharing', 'prime-hosting' ),
			'description' => __( 'Enable a Twitter sharing link.', 'prime-hosting' ),
			'section'     => 'social_options',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'sharing_linkedin',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'sharing_linkedin',
		array(
			'label'       => __( 'LinkedIn Social Sharing', 'prime-hosting' ),
			'description' => __( 'Enable a LinkedIn sharing link.', 'prime-hosting' ),
			'section'     => 'social_options',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'sharing_reddit',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'sharing_reddit',
		array(
			'label'       => __( 'Reddit Social Sharing', 'prime-hosting' ),
			'description' => __( 'Enable a Reddit sharing link.', 'prime-hosting' ),
			'section'     => 'social_options',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'sharing_pinterest',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'sharing_pinterest',
		array(
			'label'       => __( 'Pinterest Social Sharing', 'prime-hosting' ),
			'description' => __( 'Enable a Pinterest sharing link.', 'prime-hosting' ),
			'section'     => 'social_options',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'social_links_footer',
		array(
			'default'           => true,
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
		)
	);

	$wp_customize->add_control(
		'social_links_footer',
		array(
			'label'       => __( 'Show Social Links in the footer', 'prime-hosting' ),
			'description' => __( 'You also need to add the links in the options below.', 'prime-hosting' ),
			'section'     => 'social_links',
			'type'        => 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'facebook_link',
		array(
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		)
	);

	$wp_customize->add_control(
		'facebook_link',
		array(
			'type'        => 'url',
			'label'       => __( 'Enter your Facebook link:', 'prime-hosting' ),
			'description' => __( 'Leave this setting blank to disable it.', 'prime-hosting' ),
			'section'     => 'social_links',
		)
	);

	$wp_customize->add_setting(
		'twitter_link',
		array(
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		)
	);

	$wp_customize->add_control(
		'twitter_link',
		array(
			'type'        => 'url',
			'label'       => __( 'Enter your Twitter link:', 'prime-hosting' ),
			'description' => __( 'Leave this setting blank to disable it.', 'prime-hosting' ),
			'section'     => 'social_links',
		)
	);

	$wp_customize->add_setting(
		'instagram_link',
		array(
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		)
	);

	$wp_customize->add_control(
		'instagram_link',
		array(
			'type'        => 'url',
			'label'       => __( 'Enter your Instragram link:', 'prime-hosting' ),
			'description' => __( 'Leave this setting blank to disable it.', 'prime-hosting' ),
			'section'     => 'social_links',
		)
	);

	$wp_customize->add_setting(
		'linkedin_link',
		array(
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		)
	);

	$wp_customize->add_control(
		'linkedin_link',
		array(
			'type'        => 'url',
			'label'       => __( 'Enter your LinkedIn link:', 'prime-hosting' ),
			'description' => __( 'Leave this setting blank to disable it.', 'prime-hosting' ),
			'section'     => 'social_links',
		)
	);

	$wp_customize->add_setting(
		'youtube_link',
		array(
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		)
	);

	$wp_customize->add_control(
		'youtube_link',
		array(
			'type'        => 'url',
			'label'       => __( 'Enter your Youtube link:', 'prime-hosting' ),
			'description' => __( 'Leave this setting blank to disable it.', 'prime-hosting' ),
			'section'     => 'social_links',
		)
	);

	$wp_customize->add_setting(
		'pinterest_link',
		array(
			'sanitize_callback' => 'esc_url_raw',
			'default'           => '',
		)
	);

	$wp_customize->add_control(
		'pinterest_link',
		array(
			'type'        => 'url',
			'label'       => __( 'Enter your Pinterest link:', 'prime-hosting' ),
			'description' => __( 'Leave this setting blank to disable it.', 'prime-hosting' ),
			'section'     => 'social_links',
		)
	);

	$wp_customize->add_setting(
		'show_featured_image',
		array(
			'sanitize_callback' => 'prime_hosting_sanitize_checkbox',
			'default'           => true,
		)
	);

	$wp_customize->add_control(
		'show_featured_image',
		array(
			'type'     => 'checkbox',
			'label'    => __( 'Show featured images in archives', 'prime-hosting' ),
			'section'  => 'image_section',
			'priority' => 10,
		)
	);

}
add_action( 'customize_register', 'prime_hosting_customize_register' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function prime_hosting_customize_partial_blogname() {
	bloginfo( 'name' );
}

/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function prime_hosting_customize_partial_blogdescription() {
	bloginfo( 'description' );
}

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function prime_hosting_customize_preview_js() {
	wp_enqueue_script( '_s-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'prime_hosting_customize_preview_js' );

/**
 * Checkbox sanitization callback, from
 * https://github.com/WPTRT/code-examples/blob/master/customizer/sanitization-callbacks.php
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool Whether the checkbox is checked.
 */
function prime_hosting_sanitize_checkbox( $checked ) {
	// Boolean check.
	return ( ( isset( $checked ) && true == $checked ) ? true : false );
}

/**
 * Select sanitization callback example.
 *
 * - Sanitization: select
 * - Control: select, radio
 * Sanitization callback for 'select' and 'radio' type controls. This callback sanitizes `$input`
 * as a slug, and then validates `$input` against the choices defined for the control.
 *
 * @see sanitize_text_field()               https://developer.wordpress.org/reference/functions/sanitize_text_field/
 * @see $wp_customize->get_control() https://developer.wordpress.org/reference/classes/wp_customize_manager/get_control/
 *
 * @param string               $input   Slug to sanitize.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string Sanitized slug if it is a valid choice; otherwise, the setting default.
 */
function prime_hosting_sanitize_select( $input, $setting ) {
	// Ensure input is a slug.
	$input = sanitize_text_field( $input );

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}
