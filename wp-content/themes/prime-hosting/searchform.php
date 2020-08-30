<?php
/**
 * The template for displaying search form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Prime Hosting
 */

?>
<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'prime-hosting' ); ?></span>
		<input type="search" class="search-field"
			placeholder="<?php echo esc_attr_x( 'Search…', 'placeholder', 'prime-hosting'  ); ?>"
			value="<?php echo get_search_query(); ?>" name="s"
			title="<?php echo esc_attr_x( 'Search for:', 'label', 'prime-hosting' ); ?>" />
	</label>
	<button class="search-submit" type="submit"> <i class="fas fa-search"> </i><span class="screen-reader-text"><?php esc_html_e( 'Search', 'prime-hosting' ); ?></span></button>
</form>
