<?php
/*Top Header Options*/
$wp_customize->add_section( 'polite_top_header_section', array(
   'priority'       => 20,
   'capability'     => 'edit_theme_options',
   'theme_supports' => '',
   'title'          => __( 'Top Header', 'polite' ),
   'panel' 		 => 'polite_panel',
) );

/*callback functions header section*/
if ( !function_exists('polite_header_active_callback') ) :
  function polite_header_active_callback(){
      global $polite_theme_options;
      $enable_header = absint($polite_theme_options['polite_enable_top_header']);
      if( 1 == $enable_header ){
          return true;
      }
      else{
          return false;
      }
  }
endif;

/*Enable Top Header Section*/
$wp_customize->add_setting( 'polite_options[polite_enable_top_header]', array(
   'capability'        => 'edit_theme_options',
   'transport' => 'refresh',
   'default'           => $default['polite_enable_top_header'],
   'sanitize_callback' => 'polite_sanitize_checkbox'
) );

$wp_customize->add_control( 'polite_options[polite_enable_top_header]', array(
   'label'     => __( 'Enable Top Header', 'polite' ),
   'description' => __('Checked to show the top header section like search and social icons', 'polite'),
   'section'   => 'polite_top_header_section',
   'settings'  => 'polite_options[polite_enable_top_header]',
   'type'      => 'checkbox',
   'priority'  => 5,
) );

/*Enable Social Icons In Header*/
$wp_customize->add_setting( 'polite_options[polite_enable_top_header_social]', array(
   'capability'        => 'edit_theme_options',
   'transport' => 'refresh',
   'default'           => $default['polite_enable_top_header_social'],
   'sanitize_callback' => 'polite_sanitize_checkbox'
) );

$wp_customize->add_control( 'polite_options[polite_enable_top_header_social]', array(
   'label'     => __( 'Enable Social Icons', 'polite' ),
   'description' => __('You can show the social icons here. Manage social icons from Appearance > Menus. Social Menu will display here.', 'polite'),
   'section'   => 'polite_top_header_section',
   'settings'  => 'polite_options[polite_enable_top_header_social]',
   'type'      => 'checkbox',
   'priority'  => 5,
   'active_callback'=>'polite_header_active_callback'
) );

/*Enable Menu in top Header*/
$wp_customize->add_setting( 'polite_options[polite_enable_top_header_menu]', array(
    'capability'        => 'edit_theme_options',
    'transport' => 'refresh',
    'default'           => $default['polite_enable_top_header_menu'],
    'sanitize_callback' => 'polite_sanitize_checkbox'
) );

$wp_customize->add_control( 'polite_options[polite_enable_top_header_menu]', array(
    'label'     => __( 'Menu in Header', 'polite' ),
    'description' => __('Top Header Menu will display here. Go to Appearance < Menu.', 'polite'),
    'section'   => 'polite_top_header_section',
    'settings'  => 'polite_options[polite_enable_top_header_menu]',
    'type'      => 'checkbox',
    'priority'  => 5,
    'active_callback'=>'polite_header_active_callback'
) );
