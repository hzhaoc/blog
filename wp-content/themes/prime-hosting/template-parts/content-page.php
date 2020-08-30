<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Prime Hosting
 */

$prime_hosting_has_thumbnail = '';
if ( has_post_thumbnail() && get_theme_mod( 'show_featured_image', true ) === true ) {
	$prime_hosting_has_thumbnail = 'has_thumbnail';
}

if ( is_singular() ) {
	$prime_hosting_post_class = 'blog_post card border-0 ';
} else {
	$prime_hosting_post_class = 'blog_post card border-0 shadow ';
}
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $prime_hosting_post_class . $prime_hosting_has_thumbnail ); ?>>
	<?php
	prime_hosting_post_thumbnail();
	if ( is_singular() ) {
		prime_hosting_social_sharing();
	}
	?>
	<div class="card-body positon-relative">
		<?php
		if ( is_singular() && get_theme_mod( 'sharing_pages', false ) === true ) {
			?>
			<a data-toggle="collapse" data-target="#social_share" class="btn_detail"><i class="fas fa-share-alt"></i></a>
			<?php
		}
		if ( is_singular() ) {
			the_title( '<h2 class="card-title">', '</h2>' );
		} else {
			the_title( '<h2 class="card-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		}

		if ( ! is_singular() ) {
			the_excerpt();
		} elseif ( is_singular() ) {
			the_content();

			wp_link_pages(
				array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'prime-hosting' ),
					'after'  => '</div>',
				)
			);
		}
		?>
	</div><!-- End card body -->


</article><!-- #post-<?php the_ID(); ?> -->
