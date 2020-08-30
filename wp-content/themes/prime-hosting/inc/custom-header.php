<?php
/**
 * Sample implementation of the Custom Header feature
 *
 * You can add an optional custom header image to header.php like so ...
 *
 * <?php the_header_image_tag(); ?>
 *
 * @link https://developer.wordpress.org/themes/functionality/custom-headers/
 *
 * @package Prime Hosting
 */

/**
 * Set up the WordPress core custom header feature.
 *
 * @uses prime_hosting_header_style()
 */
function prime_hosting_custom_header_setup() {
	add_theme_support(
		'custom-header',
		apply_filters(
			'prime_hosting_custom_header_args',
			array(
				'default-image'      => get_template_directory_uri() . '/images/header.png',
				'default-text-color' => '1d3571',
				'width'              => 500,
				'height'             => 400,
				'flex-height'        => true,
				'wp-head-callback'   => 'prime_hosting_header_style',
			)
		)
	);

	register_default_headers(
		array(
			'network' => array(
				'url'           => '%s/images/header.png',
				'thumbnail_url' => '%s/images/header.png',
				'description'   => __( 'Network', 'prime-hosting' ),
			),
		)
	);
}

add_action( 'after_setup_theme', 'prime_hosting_custom_header_setup' );

if ( ! function_exists( 'prime_hosting_header_style' ) ) :
	/**
	 * Styles the header image and text displayed on the blog.
	 *
	 * @see prime_hosting_custom_header_setup().
	 */
	function prime_hosting_header_style() {
		$header_text_color = get_header_textcolor();
		// If we get this far, we have custom styles. Let's do this.
		?>
		<style type="text/css">
		<?php
		// Has the text been hidden?
		if ( ! display_header_text() ) {
			?>
			.site-title,
			.site-description {
				position: absolute;
				clip: rect(1px, 1px, 1px, 1px);
			}
			<?php
			// If the user has set a custom color for the text use that.
		} else {
			?>
			.site-title a,
			.site-description {
				color: #<?php echo esc_attr( $header_text_color ); ?>;
			}
			<?php
		};
		?>
		</style>
		<?php
	}
endif;
