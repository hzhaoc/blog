<?php
/**
 * Recommended way to include parent theme styles.
 * (Please see http://codex.wordpress.org/Child_Themes#How_to_Create_a_Child_Theme)
 *
 */
/**
 * Loads the child theme textdomain.
 */
function polite_grid_load_language() {
    load_child_theme_textdomain( 'polite-grid' );
}
add_action( 'after_setup_theme', 'polite_grid_load_language' );

/**
 * Enqueue Style for child theme.
 */
add_action( 'wp_enqueue_scripts', 'polite_grid_enqueue_scripts');
function polite_grid_enqueue_scripts() {

        /*google font  */
    global $polite_theme_options;
    $polite_grid_name_font_url   = esc_attr( $polite_theme_options['polite-font-family-url'] );  

    wp_enqueue_style( 'polite-grid-fonts', '//fonts.googleapis.com/css?family='.$polite_grid_name_font_url );

    $parent_style = 'polite-style-child';
    $polite_grid_version = wp_get_theme(get_template())->get( 'Version' );

    wp_enqueue_style('polite-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'polite-grid-style', get_stylesheet_directory_uri() . '/style.css', array(), $polite_grid_version );;
}

/**
 * Polite Theme Customizer and overide from child theme
 *
 * @package Polite
 */

if ( !function_exists('polite_default_theme_options_values') ) :

    function polite_default_theme_options_values() {

        $default_theme_options = array(

            /*Logo Options*/
            'polite_logo_width_option' => '300',

            /*Top Header*/
            'polite_enable_top_header'=> 0, 
            'polite_enable_top_header_social'=> 0,
            'polite_enable_top_header_menu'=> 0,

           /*Header Options*/
            'polite_enable_offcanvas'  => 0,
            'polite_enable_search'  => 0,

            /*Colors Options*/
            'polite_primary_color'              => '#d42929',

            /*Slider Options*/
            'polite_enable_slider'      => 0,
            'polite-select-category'    => 0,
    
            /*Boxes Section */
            'polite_enable_promo'       => 1,
            'polite-promo-select-category'=> 0,
            
            /*Blog Page*/
            'polite-sidebar-blog-page' => 'right-sidebar',
            'polite-column-blog-page'  => 'one-column',
            'polite-blog-image-layout' => 'left-image',
            'polite-content-show-from' => 'excerpt',
            'polite-excerpt-length'    => 25,
            'polite-pagination-options'=> 'numeric',
            'polite-read-more-text'    => '',
            'polite-show-hide-share'   => 1,
            'polite-show-hide-category'=> 1,
            'polite-show-hide-date'=> 1,
            'polite-show-hide-author'=> 1,
            'polite-show-hide-read-time'=>1,
            'polite-font-family-url'=>'Muli',

            /*Single Page */
            'polite-single-page-featured-image' => 1,
            'polite-single-page-related-posts'  => 0,
            'polite-single-page-related-posts-title' => esc_html__('Related Posts','polite-grid'),
            'polite-sidebar-single-page'=> 'single-right-sidebar',
            'polite-single-social-share' => 1,


            /*Sticky Sidebar*/
            'polite-enable-sticky-sidebar' => 1,

            /*Footer Section*/
            'polite-footer-copyright'  => esc_html__('Copyright All Rights Reserved 2020','polite-grid'),

            /*Breadcrumb Options*/
            'polite-extra-breadcrumb' => 1,

        );
return apply_filters( 'polite_default_theme_options_values', $default_theme_options );
}
endif;

function polite_grid_sanitize_select( $input, $setting ) {
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function polite_grid_customize_register( $wp_customize ) {

   $default = polite_default_theme_options_values();

       /*Blog Page Show content from*/
    $wp_customize->add_setting('polite_options[polite-content-show-from]', array(
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
        'default' => $default['polite-content-show-from'],
        'sanitize_callback' => 'polite_sanitize_select'
    ));

    $wp_customize->add_control('polite_options[polite-content-show-from]', array(
        'choices' => array(
            'excerpt' => __('Show from Excerpt', 'polite-grid'),
            'content' => __('Show from Content', 'polite-grid'),
            'hide'    => __('Hide Content', 'polite-grid'),
        ),
        'label' => __('Select Content Display From', 'polite-grid'),
        'description' => __('You can enable excerpt from Screen Options inside post section of dashboard', 'polite-grid'),
        'section' => 'polite_blog_page_section',
        'settings' => 'polite_options[polite-content-show-from]',
        'type' => 'select',
        'priority' => 12,
    ));

    /*Read time Show hide*/
    $wp_customize->add_setting('polite_options[polite-show-hide-read-time]', array(
        'capability' => 'edit_theme_options',
        'transport' => 'refresh',
        'default' => $default['polite-show-hide-read-time'],
        'sanitize_callback' => 'polite_sanitize_checkbox'
    ));

    $wp_customize->add_control('polite_options[polite-show-hide-read-time]', array(
        'label' => __('Show Read Time', 'polite-grid'),
        'description' => __('Option to hide the read time on the blog page.', 'polite-grid'),
        'section' => 'polite_blog_page_section',
        'settings' => 'polite_options[polite-show-hide-read-time]',
        'type' => 'checkbox',
        'priority' => 15,
    ));

    /*Font Family URL*/
        $wp_customize->add_setting( 'polite_options[polite-font-family-url]', array(
            'capability'        => 'edit_theme_options',
            'transport' => 'refresh',
            'default'           => $default['polite-font-family-url'],
            'sanitize_callback' => 'polite_grid_sanitize_select'
        ) );
        $choices = polite_grid_google_fonts();
        $wp_customize->add_control( 'polite_options[polite-font-family-url]', array(
            'label'     => __( 'URL of Font Family', 'polite-grid' ),
            'description' => sprintf('%1$s <a href="%2$s" target="_blank">%3$s</a> %4$s',
                        __( 'Select the font here. More options are available', 'polite-grid' ),
                        esc_url('https://www.templatesell.com/item/polite-plus-masonry-wordpress-theme/'),
                        __('in the premium version Polite Plus' , 'polite-grid'),
                        __('check now.' ,'polite-grid')
            ),
            'choices'   => $choices,
            'section'   => 'polite_blog_page_section',
            'settings'  => 'polite_options[polite-font-family-url]',
            'type'      => 'select',
            'priority'  => 15,
        ) );


}
add_action( 'customize_register', 'polite_grid_customize_register', 999 );


/* Word read count Pagination */
if (!function_exists('polite_grid_read_time')) :
    /**
     * @param $content
     *
     * @return string
     */
    function polite_grid_read_time()
    {
        $content = apply_filters('the_content', get_post_field('post_content'));
        $read_words = 200;
        $decode_content = html_entity_decode($content);
        $filter_shortcode = do_shortcode($decode_content);
        $strip_tags = wp_strip_all_tags($filter_shortcode, true);
        $count = str_word_count($strip_tags);
        $word_per_min = (absint($count) / $read_words);
        $word_per_min = ceil($word_per_min);

        if (absint($word_per_min) > 0) {
            $word_count_strings = sprintf(_n('%s Min Reading', '%s Min Reading', number_format_i18n($word_per_min), 'polite-grid'), number_format_i18n($word_per_min));
            if ('post' == get_post_type()):
                echo '<span class="min-read">';
                echo esc_html($word_count_strings);
                echo '</span>';
            endif;

        }
    }
endif;



/**
 * Google Fonts
 *
 * @param null
 * @return array
 *
 * @since Prefer 1.0.0
 *
 */
if (!function_exists('polite_grid_google_fonts')) :
    function polite_grid_google_fonts()
    {
        $polite_grid_google_fonts = array(
            'Muli' => 'Muli',
            'Lato' => 'Lato',
            'Open+Sans' => 'Open Sans',
            'Montserrat' => 'Montserrat',
            'Alegreya:400,400italic,700,900' => 'Alegreya',
            'Alex+Brush' => 'Alex Brush'
        );
        return apply_filters('polite_grid_google_fonts', $polite_grid_google_fonts);
    }
endif;


/**
 * Enqueue the list of fonts.
 */
function polite_grid_customizer_fonts()
{
    wp_enqueue_style('polite_grid_customizer_fonts', 'https://fonts.googleapis.com/css?family=Muli|Lato|Open+Sans| Montserrat|Alegreya', array(), null);
}

add_action('customize_controls_print_styles', 'polite_grid_customizer_fonts');
add_action('customize_preview_init', 'polite_grid_customizer_fonts');

add_action(
    'customize_controls_print_styles',
    function (){
        ?>
        <style>
            <?php
            $arr = array( 'Muli','Lato','Open+Sans',' Montserrat','Alegreya');

            foreach ( $arr as $font ) {
                $font_family = str_replace("+", " ", $font);
                echo '.customize-control select option[value*="' . $font . '"] {font-family: ' . $font_family . '; font-size: 22px;}';
            }
            ?>
        </style>
        <?php
    }
);



if (!function_exists('polite_grid_dynamic_css')) :

    function polite_grid_dynamic_css()
    {
        global $polite_theme_options;
        $polite_grid_google_fonts = polite_grid_google_fonts(); 
        $polite_font_family = $polite_theme_options['polite-font-family-url'];       
        /* Paragraph Font Options */
        $polite_font_body_family = esc_attr($polite_grid_google_fonts[$polite_font_family] );

        $custom_css = '';
        //Paragraph Font Options 
        if (!empty($polite_font_body_family)) {
            $custom_css .= "
            body,
            .entry-content p{ 
                font-family:".$polite_font_body_family."; 
            }";
        }

        wp_add_inline_style('polite-style', $custom_css);
    }
endif;
add_action('wp_enqueue_scripts', 'polite_grid_dynamic_css', 99);